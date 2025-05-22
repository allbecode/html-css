<?php

// Falta:
// 
// 1- Eliminar os comentário indevidos;
// 2- Verificar pq o relatório não está atualizando as data depois de editadas.


include 'db_connection.php';
include 'header.php';

$receitas = [];
$despesas = [];
$totalReceitas = 0;
$totalDespesas = 0;
$saldoFinal = 0;
$mesAtual = date('m'); // De 1 a 12
$anoAtual = date('Y');
$mes = $_POST['mes'] ?? $mesAtual;
$ano = $_POST['ano'] ?? $anoAtual;

$sql = "SELECT * FROM transacoes WHERE mes = :mes AND ano = :ano ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':mes', $mes);
$stmt->bindParam(':ano', $ano);
$stmt->execute();
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($transacoes as $transacao) {
    if ($transacao['tipo'] === 'receita') {
        $receitas[] = $transacao;
        $totalReceitas += $transacao['valor'];
    } else {
        $despesas[] = $transacao;
        $totalDespesas += $transacao['valor'];
    }
}

$saldoFinal = $totalReceitas - $totalDespesas;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro</title>

    <link rel="stylesheet" href="styles-principal.css">
    <!-- <link rel="stylesheet" href="style-form.css"> -->
    <link rel="stylesheet" href="styles-tables.css">
    <link rel="stylesheet" href="style-lista-transacoes.css">
    <link rel="stylesheet" href="media_queries.css">

    <style>
        /* #form-mes-ano {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            position: relative;
        }*/

        #btn-imprimir-relatorio {
            /* margin-left: auto; */
            padding: 6px 10px;
            background: none;
            /* color: #fff; */
            border: none;
            font-size: 25px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        /* #btn-imprimir-relatorio:hover { */
            /* background-color: #555; */
        /* } */

        @media print {

            /* Oculta cabeçalho, rodapé, menus e formulários */
            /* header, */
            /* footer, */
            nav,
            /* #form-mes-ano, */
            #form-contribuicao,
            .button,
            /* .acoes, */
            #btn-imprimir-relatorio,
            #menu,
            .container-form p,
            .mensagem-flutuante {
                display: none !important;
            }

            /* Exibe apenas o conteúdo principal do relatório */
            main {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            /* Tabela: remove estilos desnecessários e força quebra de página adequada */
            table {
                width: 100%;
                font-size: 8pt;
                border-collapse: collapse;
                page-break-inside: auto;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            /* Remove margens automáticas que alguns navegadores adicionam */
            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
            @page {
                    size: landscape;
                }
        }
    </style>

    <!-- <script src="script-form.js" defer></script> -->
     <script src="script-contribuicoes.js"></script>
</head>

<body class="relatorio-transacoes">
    <main>
        <h2 class="no-print">Consultar Transações</h2>
        <div class="container-form">
            <p>Altere uma das opções abaixo para visualizar os dados. </p>
            <form method="POST" class="form-filtro" id="form-contribuicao">

                <label for="mes">Mês:</label>
                <select id="mes" name="mes" required>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $mes ? 'selected' : '' ?>>
                            <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <label for="ano">Ano:</label>
                <input type="number" name="ano" id="ano" value="<?= $ano ?>" required>

                <!-- <button type="submit">Consultar</button> -->

                <button type="button" id="btn-imprimir-relatorio" title="Imprimir relatório">
                    🖨️
                </button>
            </form>
        </div>


        <?php if (!empty($receitas) || !empty($despesas)): ?>
            <h1>Relatório de <?php echo $mes . '/' . $ano; ?></h1>

            <h2>Receitas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Forma de Pagamento</th>
                        <th>Descrição</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($receitas as $receita): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receita['nome']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($receita['data_vencimento'])); ?></td>
                            <td>R$ <?php echo number_format($receita['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo $receita['forma_pagamento']; ?></td>
                            <td><?php echo htmlspecialchars($receita['descricao']); ?></td>
                            <td class="<?php echo $receita['pago'] ? 'status-pago' : 'status-nao-pago'; ?>">
                                <?php echo $receita['pago'] ? '✔' : '✖'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            Total de Receitas: R$ <?php echo number_format($totalReceitas, 2, ',', '.'); ?>
                        </td>

                    </tr>
                </tfoot>
            </table>

            <h2>Despesas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Forma de Pagamento</th>
                        <th>Descrição</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($despesas as $despesa): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($despesa['nome']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($despesa['data_vencimento'])); ?></td>
                            <td>R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo $despesa['forma_pagamento']; ?></td>
                            <td><?php echo htmlspecialchars($despesa['descricao']); ?></td>
                            <td class="<?php echo $despesa['pago'] ? 'status-pago' : 'status-nao-pago'; ?>">
                                <?php echo $despesa['pago'] ? '✔' : '✖'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            Total de Despesas: R$ <?php echo number_format($totalDespesas, 2, ',', '.'); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <h2>Resumo Financeiro</h2>
            <div class="resumo-financeiro">
                <p><strong>Total de Receitas:</strong> R$ <?php echo number_format($totalReceitas, 2, ',', '.'); ?></p>
                <p><strong>Total de Despesas:</strong> R$ <?php echo number_format($totalDespesas, 2, ',', '.'); ?></p>
                <p><strong>Saldo Final:</strong> R$ <?php echo number_format($saldoFinal, 2, ',', '.'); ?></p>
            </div>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const botaoImprimir = document.getElementById('btn-imprimir-relatorio');

            if (botaoImprimir) {
                botaoImprimir.addEventListener('click', () => {
                    window.print();
                });
            }
        });
    </script>
</body>

</html>