<?php
require_once '../controllers/controller_report_transactions.php';
require_once '../includes/functions.php';
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Relatório Financeiro</title> -->

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/form-global.css">
    <link rel="stylesheet" href="../assets/css/segmentation/relatorio-transacoes.css">


    <script src="../assets/js/script-contribuicoes.js"></script>
    <script src="../assets/js/script-relatório-financeiro.js" defer  ></script>

</head>

<body>
    <main>
        <h2 class="no-print">Consultar Transações</h2>
        <div class="container-form">
            <p class="center">Altere uma das opções abaixo para visualizar os dados. </p>
            <form method="POST" class="form-geral" id="form-contribuicao">

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

                <button type="button" id="btn-imprimir-relatorio" title="Imprimir relatório">
                    🖨️
                </button>
            </form>
        </div>
        <?php if (count($transacoes) === 0): ?>
            <div class="mensagem-sem-dados">
                Nenhuma transação registrada para o mês <strong><?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . '/' . $ano;  ?></strong>.
            </div>
        <?php else: ?>
        <?php if (!empty($receitas) || !empty($despesas)): ?>
            <h1>Relatório de <?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . '/' . $ano; ?></h1>

            <h2 class="no-print">Receitas</h2>
            <table>
                <caption>Receitas</caption>
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
                            <td><?php echo formatarDataBr($receita['data_vencimento']); ?></td>
                            <td> <?php echo formatarValor($receita['valor']); ?></td>
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
                            Total de Receitas:  <?php echo formatarValor($totalReceitas); ?>
                        </td>

                    </tr>
                </tfoot>
            </table>

            <h2 class="no-print">Despesas</h2>
            <table>
                <caption>Despesas</caption>
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
                            <td><?php echo formatarDataBr($despesa['data_vencimento']); ?></td>
                            <td> <?php echo formatarValor($despesa['valor']); ?></td>
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
                            Total de Despesas:  <?php echo formatarValor($totalDespesas); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <h2>Resumo Financeiro</h2>
            <div class="resumo-financeiro">
                <p><strong>Total de Receitas:</strong>  <?php echo formatarValor($totalReceitas); ?></p>
                <p><strong>Total de Despesas:</strong>  <?php echo formatarValor($totalDespesas); ?></p>
                <p><strong>Saldo Final:</strong>  <?php echo formatarValor($saldoFinal); ?></p>
            </div>
        <?php endif; ?>
        <?php endif;?>

    </main>
    <?php include '../includes/footer.php'; ?>
</body>

</html>