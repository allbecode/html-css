<?php

// Se um carro não possui revisões vencidas o veículo tbm deverá desaparecer da tela de lembretes;

// Melhorar a aparencia da tela de lembretes;

// No histórico de manutenções, as manutenções agora poderão ter como parâmetro não só a data para a próxima revisão, mas tbm se estão ou não marcadas como revisadas. Caso estejam vencidas, mas revisadas, as mesmas deverão permanecer sendo apresentadas no histórico, porém não deverão estr mais disponíveis para edição ou exclusão, somete visualização;


require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';

// Verifica login
$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    header("Location: ../acsses_control/pages/login.php");
    exit;
}

// Consulta manutenções críticas (vencidas ou a vencer em 30 dias, não revisadas)
$stmt = $pdo->prepare("
    SELECT 
        m.id, 
        m.carro_id, 
        c.apelido AS carro_nome, 
        t.nome AS tipo_nome, 
        m.proxima_manut_data, 
        m.proxima_manut_km,
        m.data,
        m.km,
        m.valor,
        m.local,
        m.descricao
    FROM manutencoes_carro m
    JOIN carros c ON m.carro_id = c.id
    JOIN tipos_manutencao t ON m.tipo_id = t.id
    WHERE m.usuario_id = :usuario
      AND m.revisado = 0
      AND (
          (m.proxima_manut_data < CURDATE()) 
          OR (m.proxima_manut_data BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY))
      )
    ORDER BY c.apelido, m.proxima_manut_data ASC
");
$stmt->execute(['usuario' => $usuarioId]);
$manutencoes = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Agrupar por carro
$agrupado = [];
foreach ($manutencoes as $m) {
    $agrupado[$m['carro_id']]['nome'] = $m['carro_nome'];
    $agrupado[$m['carro_id']]['manutencoes'][] = $m;
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Lembretes de Manutenções</title>

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">

    <style>
        /* Container geral dos cards */
        .cards-container {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }

        /* Card de cada carro */
        .card-carro {
            background: #fff;
            border-radius: 10px;
            padding: 1.2rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-left: 6px solid #0077cc;
        }

        .card-carro h2 {
            margin-bottom: 0.5rem;
        }

        .link-lista {
            /* display: inline-block; */
            /* margin-bottom: 0.8rem; */
            /* font-size: 0.9rem; */
            /* color: #0077cc; */
            text-decoration: none;
            /* font-weight: bold; */
        }

        /* .link-lista:hover {
            text-decoration: underline;
        } */

        /* Lista de manutenções dentro do card */
        .card-carro ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .manutencao {
            padding: 0.6rem;
            margin-bottom: 0.6rem;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            cursor: pointer;
            /* Indica que é clicável */
        }

        .manutencao.vencida {
            border-left: 6px solid red;
        }

        .manutencao.a-vencer {
            border-left: 6px solid orange;
        }

        /* Modal: fundo escuro */
        .modal {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .modal.hidden {
            display: none;
            opacity: 0;
        }

        /* Conteúdo do modal */
        .modal-content {
            background: #fff;
            padding: 1.5rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* Botão fechar */
        .modal-content .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            cursor: pointer;
        }

        /* Formulário dentro do modal */
        #form-revisado {
            text-align: center;
            margin-top: 1rem;
        }

        #form-revisado label {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }


        #form-revisado input[type="checkbox"] {
            margin-right: 0.5rem;
            width: 20px;
            height: 20px;
            accent-color: green;
        }

        #form-revisado button {
            padding: 0.5rem 1rem;
            background: #0077cc;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #form-revisado button:hover {
            background: #005fa3;
        }

        /* Pequenos ajustes de texto */
        .modal-content p {
            margin: 0.3rem 0;
        }



        @media screen and (min-widht:768px) {}
    </style>
</head>

<body>
    <main>
        <h1>Lembretes de Manutenções</h1>
        <?php if (empty($agrupado)): ?>
            <p>✅ Nenhuma manutenção vencida ou a vencer.</p>
        <?php else: ?>
            <div class="cards-container">
                <?php foreach ($agrupado as $carroId => $carro): ?>
                    <div class="card-carro">
                        <a href="list_manutencoes.php?carro_id=<?= $carroId ?>" class="link-lista" title="Verificar detalhes dos serviços">
                            <h2>🚗 <?= htmlspecialchars($carro['nome']) ?></h2>
                        </a>
                        <ul>
                            <?php foreach ($carro['manutencoes'] as $m): ?>
                                <?php
                                $hoje = new DateTime();
                                $proxima = new DateTime($m['proxima_manut_data']);
                                $msg = "";
                                $classe = "";
                                if ($proxima < $hoje) {
                                    $dias = $hoje->diff($proxima)->days;
                                    $msg = "🔴 Vencida há {$dias} dias";
                                    $classe = "vencida";
                                } else {
                                    $dias = $hoje->diff($proxima)->days;
                                    $msg = "🟠 A vencer em {$dias} dias";
                                    $classe = "a-vencer";
                                }
                                ?>
                                <li class="manutencao card-manutencao <?= $classe ?>"
                                    id="manutencao-<?= $m['id'] ?>"
                                    data-id="<?= $m['id'] ?>"
                                    data-data="<?= date('d/m/Y', strtotime($m['data'])) ?>"
                                    data-tipo="<?= htmlspecialchars($m['tipo_nome']) ?>"
                                    data-valor="<?= isset($m['valor']) ? 'R$ ' . number_format((float)$m['valor'], 2, ',', '.') : '—' ?>"
                                    data-km="<?= isset($m['km']) ? number_format((float)$m['km'], 0, ',', '.') . ' km' : '—' ?>"
                                    data-local="<?= htmlspecialchars($m['local'] ?? '—') ?>"
                                    data-descricao="<?= htmlspecialchars($m['descricao'] ?? '—') ?>">
                                    <p><strong>Serviço:</strong> <?= htmlspecialchars($m['tipo_nome']) ?></p>
                                    <p><strong>Data para revisão:</strong> <?= date('d/m/Y', strtotime($m['proxima_manut_data'])) ?></p>
                                    <p><strong>Status:</strong> <?= $msg ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </main>

    <!-- Modal -->
    <div id="modalManutencao" class="modal hidden">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Detalhes da Manutenção</h2>
            <p><strong>Data:</strong> <span id="modal-data"></span></p>
            <p><strong>Serviço:</strong> <span id="modal-tipo"></span></p>
            <p><strong>Valor:</strong> <span id="modal-valor"></span></p>
            <p><strong>Km:</strong> <span id="modal-km"></span></p>
            <p><strong>Local:</strong> <span id="modal-local"></span></p>
            <p><strong>Descrição:</strong> <span id="modal-descricao"></span></p>

            <form id="form-revisado">
                <input type="hidden" name="id" id="modal-id">
                    <label>
                        <input type="checkbox" name="revisado" value="1">
                        Marcar como revisado
                    </label>
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>



    <script>
        // script para marcar revisado
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".revisado-check").forEach(chk => {
                chk.addEventListener("change", function() {
                    if (this.checked) {
                        const manutencaoId = this.dataset.id;

                        fetch("../actions/marcar_revisado.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: "id=" + encodeURIComponent(manutencaoId)
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById("card-" + manutencaoId).remove();
                                } else {
                                    alert("Erro ao marcar revisão.");
                                    this.checked = false;
                                }
                            })
                            .catch(err => {
                                console.error("Erro:", err);
                                this.checked = false;
                            });
                    }
                });
            });
        });



        // Abrir/fechar modal
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modalManutencao");
            const closeBtn = modal.querySelector(".close");
            const formRevisado = document.getElementById("form-revisado");

            // Abrir modal ao clicar em qualquer card de manutenção
            document.querySelectorAll(".card-manutencao").forEach(card => {
                card.addEventListener("click", () => {
                    // Carregar os detalhes do card no modal
                    document.getElementById("modal-id").value = card.dataset.id;
                    document.getElementById("modal-data").innerText = card.dataset.data;
                    document.getElementById("modal-tipo").innerText = card.dataset.tipo;
                    document.getElementById("modal-valor").innerText = card.dataset.valor;
                    document.getElementById("modal-km").innerText = card.dataset.km;
                    document.getElementById("modal-local").innerText = card.dataset.local;
                    document.getElementById("modal-descricao").innerText = card.dataset.descricao;

                    // Mostrar modal
                    modal.classList.remove("hidden");
                });
            });

            // Fechar modal no botão X
            closeBtn.addEventListener("click", () => {
                modal.classList.add("hidden");
            });

            // Fechar modal com tecla ESC
            document.addEventListener("keydown", e => {
                if (e.key === "Escape") {
                    modal.classList.add("hidden");
                }
            });

            // Fecha modal ao clicar em qualquer lugar da tela fora do modal
            window.addEventListener("click", (e) => {
                if(e.target === modal){
                    modal.classList.add("hidden");
                }
            });

            // Enviar marcação revisado via AJAX
            formRevisado.addEventListener("submit", e => {
                e.preventDefault();

                const formData = new FormData(formRevisado);

                fetch("../actions/marcar_revisado.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            // Mensagem de sucesso
                            alert("Manutenção marcada como revisada!");
                            modal.classList.add("hidden");

                            // Remover ou atualizar o card na tela
                            const card = document.querySelector(`.card-manutencao[data-id="${formData.get("id")}"]`);
                            if (card) card.remove();
                        } else {
                            alert("Erro: " + (res.message || "Falha ao atualizar"));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Erro na comunicação com o servidor.");
                    });
            });
        });
    </script>
</body>

</html>