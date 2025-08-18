<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controllers/controller_relatorio_global_controbuicao.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Global de Contribuições</title>
    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/relatorio-contribuicao.css">
    <script src="../assets/js/utils.js" defer></script>
    <script src="../assets/js/scripts.js" defer></script>
</head>
<body class="<?= $pageClass ?>">
<main>

    <h2>Relatório Global de Contribuições</h2>

    <?php if (!empty($contribuicoes)): ?>
        <div class="container">
            <table>
                <caption>
                    <p><strong>Mês:</strong> <?= str_pad($mes, 2, '0', STR_PAD_LEFT); ?> / <?= $ano; ?></p>
                </caption>
                <thead>
                    <tr>
                        <th>Contribuição</th>
                        <?php foreach ($colunas as $coluna): ?>
                            <th><?= htmlspecialchars($coluna) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contribuicoes as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['nome']); ?></td>
                            <?php 
                                $valorDividido = $c['valor'] / count($colunas);
                                foreach ($colunas as $_): ?>
                                <td><?= formatarValor($valorDividido); ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>

                    <tr class="subTotal">
                        <td>Total</td>
                        <?php 
                            $totalDividido = $total / count($colunas);
                            foreach ($colunas as $_): ?>
                            <td><?= formatarValor($totalDividido); ?></td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="<?= 1 + count($colunas) ?>">
                            <strong>Total Geral:</strong> <?= formatarValor($total); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="no-print">
                <button title="Imprimir Relatório" class="button-icon" onclick="imprimirLista()">🖨️</button>
                <button title="Fechar Relatório" class="button-icon" onclick="fecharRelatorio()">❌</button>
            </div>
        </div>
    <?php else: ?>
        <p>Nenhuma contribuição encontrada para o período selecionado.</p>
    <?php endif; ?>

</main>
<?php include '../includes/footer.php'; ?>
</body>
</html>
