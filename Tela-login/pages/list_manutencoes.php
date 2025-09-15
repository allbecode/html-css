<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/carros_functions.php';
// require_once '../includes/header.php';
require_once '../includes/manutencoes_functions.php';

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'] ?? null;
$carroId = isset($_GET['carro_id']) ? (int) $_GET['carro_id'] : null;

if (!$carroId) {
    die("Carro não especificado.");
}

$carro = buscarCarroPorId($pdo, $carroId, $usuarioId);
$manutencoes = buscarManutencoesPorCarro($pdo, $carroId, $usuarioId);
$resumoCustos = calcularResumoCustos($pdo, $carroId, $usuarioId);

$stmt = $pdo->prepare("
    SELECT 
        m.*, 
        t.nome AS tipo_nome,
        fp.id AS forma_pagamento_id,
        fp.nome AS forma_pagamento_nome
    FROM manutencoes_carro m
    LEFT JOIN tipos_manutencao t ON m.tipo_id = t.id
    LEFT JOIN forma_pagamento fp ON m.forma_pagamento_id = fp.id
    WHERE m.carro_id = :carro_id AND m.usuario_id = :usuario_id
    ORDER BY m.data DESC
");
$stmt->execute([
    'carro_id' => $carroId,
    'usuario_id' => $usuarioId
]);
$manutencoes = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFinD - Manutenções</title>

    <!-- CSS Global -->
    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">

    <!-- CSS específico da página -->
    <link rel="stylesheet" href="../assets/css/segmentation/layout_list_manutencoes.css">

    <link rel="stylesheet" href="../assets/css/segmentation/modal.css">

    <style>
       
    </style>


    <script src="../assets/js/script-manutencoes.js" defer></script>
</head>

<body>


    <!-- Cabeçalho + Breadcrumb -->
    <header class="page-header">
        <h1 class="car-title"><?= htmlspecialchars($carro['apelido']) ?></h1>
        <p class="breadcrumb">
            Você está em: <span>Veículos</span> > <span><?= htmlspecialchars($carro['apelido']) ?></span> > <span>Manutenções</span>
        </p>
    </header>

    <div class="layout">

        <!-- Botão para expandir/colapsar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-toggle" id="toggleSidebar">
                <span class="arrow">➤</span>
            </div>

            <ul class="menu">
                <li>
                    <a href="list_manutencoes.php?carro_id=<?= $carroId ?>" class="menu-link">
                        <span class="icon">📋️</span>
                        <span class="label">Histórico</span>
                    </a>
                </li>
                <li>
                    <a href="#"
                        class="menu-link abrir-modal-manutencao">
                        <span class="icon">➕</span>
                        <span class="label">Nova Manutenção</span>
                    </a>
                </li>
                <li>
                    <a href="../pages/lembretes.php?carro_id=<?= $carroId?>" class="menu-link">
                        <span class="icon">⏱️</span>
                        <span class="label">Lembretes</span>
                    </a>
                </li>
                <li>
                    <a href="list_manutencoes.php?carro_id=<?= $carroId ?>" class="menu-link">
                        <span class="icon">📊</span>
                        <span class="label">Relatórios</span>
                    </a>
                </li>
                <li>
                    <a href="../pages/cadastro_carros.php" class="menu-link">
                        <span class="icon">🚙️</span>
                        <span class="label">Veículos</span>
                    </a>
                </li>
                <li>
                    <a href="../pages/index.php" class="menu-link">
                        <span class="icon">💰️</span>
                        <span class="label">Financeiro</span>
                    </a>
                </li>
            </ul>
        </div>


        <!-- Menu Mobile -->
        <div class="mobile-menu-container">

            <button id="menuToggle" class="mobile-menu-btn">☰</button>

            <nav id="mobileMenu" class="mobile-menu hidden">
                <a href="list_manutencoes.php?carro_id=<?= $carroId ?>" class="menu-link">Histórico</a>

                <a href="#" class="menu-link abrir-modal-manutencao">Nova Manutenção</a>

                <a href="lembretes.php?carro_id=<?= $carroId ?>" class="menu-link">Lembretes</a>

                <a href="../relatorios/index.php?carro_id=<?= $carroId ?>" class="menu-link">Relatórios</a>

                <a href="../pages/cadastro_carros.php" class="menu-link">Veículos</a>
            </nav>
        </div>

        <!-- Lista de Manutenções -->
        <main class="content">
            <div class="manutencoes-container">
                <h2 class="section-title">Histórico de Manutenções</h2>
                <table class="table-manutencoes" id="listaManutencoes">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Km</th>
                            <th>Valor</th>
                            <th>Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($manutencoes)): ?>
                            <?php foreach ($manutencoes as $m): ?>
                                <?php
                                // Linha extra de alerta
                                echo alertManutencaoLinha($m['proxima_manut_data'], $m['tipo_nome']);
                                ?>
                                <tr>
                                    <td data-label="Data"><?= !empty($m['data']) ? date('d/m/Y', strtotime($m['data'])) : '—' ?></td>

                                    <td data-label="Serviço"><?= htmlspecialchars($m['tipo_nome']) ?></td>

                                    <td class="detalhes hidden" data-label="Valor"><?= isset($m['valor']) ? 'R$ ' . number_format((float)$m['valor'], 2, ',', '.') : '—' ?></td>

                                    <td class="detalhes hidden" data-label="Forma de pagamento">
                                        <?= htmlspecialchars($m['forma_pagamento_nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <td class="detalhes hidden" data-label="Odômetro"><?= isset($m['km']) ? number_format((float)$m['km'], 0, ',', '.') . ' km' : '—' ?></td>

                                    <td class="detalhes hidden <?= classAlertaColuna($m['proxima_manut_data'])?>" data-label="Próxima Manutenção(data)"><?= !empty($m['proxima_manut_data']) ? date('d/m/Y', strtotime($m['proxima_manut_data'])) : '—' ?></td>

                                    <td class="detalhes hidden <?= classAlertaColuna($m['proxima_manut_data'])?>" data-label="Próxima Manutenção(km)"><?= isset($m['proxima_manut_km']) ? number_format((float)$m['proxima_manut_km'], 0, ',', '.') . ' km' : '—' ?></td>

                                    <td class="detalhes hidden" data-label="Local"><?= htmlspecialchars($m['local'] ?? '—') ?></td>

                                    <td class="detalhes hidden" data-label="Descrição"><?= htmlspecialchars($m['descricao'] ?? '—') ?></td>

                                    <td class="detalhes hidden" data-label="Pago ?"><?= isset($m['pago']) && $m['pago'] ? 'Sim' : 'Não' ?></td>
                                    <td>
                                        <!-- Botão para abrir modal -->
                                        <button class="button-icon edit-btn"
                                            data-id="<?= $m['id'] ?>"
                                            data-data="<?= $m['data'] ?>"
                                            data-tipo-id="<?= $m['tipo_id'] ?>"
                                            data-tipo-nome="<?= htmlspecialchars($m['tipo_nome'] ?? '') ?>"
                                            data-descricao="<?= esc($m['descricao']) ?>"
                                            data-km="<?= $m['km'] ?>"
                                            data-valor="<?= $m['valor'] ?>"
                                            data-form-pgto="<?= $m['forma_pagamento_id'] ?>"
                                            data-local="<?= $m['local'] ?>"
                                            data-pago="<?= $m['pago'] ?>"
                                            data-prox-data="<?= $m['proxima_manut_data'] ?>"
                                            data-prox-km="<?= $m['proxima_manut_km'] ?>"
                                            data-carro="<?= $carroId ?>"
                                            title="Editar manutenção">
                                            ✏️
                                        </button>

                                        <button
                                            class="delete-btn button-icon"
                                            data-id="<?= $m['id'] ?>"
                                            data-carro="<?= $m['carro_id'] ?>"
                                            title="Excluir manutenção">
                                            🗑️
                                        </button>

                                        <button
                                            class="toggle-details button-icon"
                                            aria-expanded="false"
                                            title="Visualizar Detalhes">
                                            👁️
                                        </button>
                                    </td>
                                </tr>
                                
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="no-data">
                                <td colspan="6">Nenhuma manutenção registrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <!-- Cards Desktop -->
        <aside class="sidebar-right">
            <div class="card card-resumo">
                <h2>Custos do último mês</h2>
                <p>R$ <?= number_format($resumoCustos['ultimo'], 2, ',', '.') ?></p>
            </div>
            <div class="card card-resumo">
                <h2>Custos deste mês</h2>
                <p>R$ <?= number_format($resumoCustos['atual'], 2, ',', '.') ?></p>
            </div>
        </aside>
    </div>

    <!-- Modal Adicionar Manutenção -->
    <div id="modalManutencao" class="modal hidden">
        <div class="modal-content">
            <span class="close-modal" id="fecharModalManutencao">&times;</span>
            <h2>Adicionar Manutenção</h2>
            <div id="alerta" class="hidden msg"></div>
            <form id="form-manutencao" action="../actions/add_manutencao.php" method="post">
                <input type="hidden" name="carro_id" value="<?= isset($carroId) ? $carroId : '' ?>">

                <label for="data">Data:</label>
                <input type="date" name="data" id="data" required>

                <label for="tipo_id">Tipo:</label>
                <select name="tipo_id" id="tipo" required></select>

                <label for="km">Odômetro:</label>
                <input type="number" name="km" id="km">

                <label for="valor">Valor (R$):</label>
                <input type="number" step="0.01" name="valor" id="valor" required>

                <label for="forma_pagamento_id">Forma de Pagamento:</label>
                <select name="forma_pagamento_id" id="forma_pagamento" required></select>

                <label for="local">Local:</label>
                <input type="text" name="local" id="local">

                <label for="proxima_manut_data">Prox. Manutenção (Data):</label>
                <input type="date" name="proxima_manut_data" id="proxima_manut_data">

                <label for="proxima_manut_km">Prox. Manutenção (km):</label>
                <input type="number" name="proxima_manut_km" id="proxima_manut_km">

                <label for="pago">Pago:</label>
                <input type="checkbox" name="pago" id="pago" value="1">

                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" rows="3"></textarea>

                <button type="submit" class="btn-submit">Salvar</button>
            </form>

        </div>
    </div>


    <!-- Modal Editar Manutenção -->
    <div id="modalEdicao" class="modal hidden">
        <div class="modal-content">
            <span class="close" id="fecharModalEdicao">&times;</span>
            <h2>Editar Manutenção</h2>
            <div id="alerta-edicao" class="hidden msg"></div>
            <form id="form-editar-manutencao" method="post" action="../actions/edit_manutencao.php">
                <input type="hidden" name="id" id="edit-id">
                <input type="hidden" name="carro_id" id="edit-carro">

                <label>Data</label>
                <input type="date" name="data" id="edit-data">

                <label>Tipo</label>
                <select name="tipo_id" id="edit-tipo"></select>

                <label>Odômetro</label>
                <input type="number" name="km" id="edit-km">

                <label>Valor</label>
                <input type="number" step="0.01" name="valor" id="edit-valor">

                <label>Forma de Pagamento</label>
                <select name="forma_pagamento_id" id="edit-form-pgto"></select>

                <label>Local</label>
                <input type="text" name="local" id="edit-local">

                <label>Próxima Manutenção (data)</label>
                <input type="date" name="proxima_manut_data" id="edit-prox-data">

                <label>Próxima Manutenção (km)</label>
                <input type="number" name="proxima_manut_km" id="edit-prox-km">

                <label>Pago</label>
                <input type="checkbox" name="pago" value="1" id="edit-pago">

                <label>Descrição</label>
                <textarea type="text" name="descricao" id="edit-descricao" rows="3"></textarea>

                <button type="submit">Salvar</button>
            </form>

        </div>
    </div>



</body>

</html>