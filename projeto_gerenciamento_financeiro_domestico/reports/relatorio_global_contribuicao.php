<?php
require_once '../controllers/controller_relatorio_global_controbuicao.php';
require_once '../includes/functions.php';
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Relatório Global de Contribuições</title> -->

    <!-- <link rel="stylesheet" href="../assets/css/styles-principal.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/style_relatorio_contribuicao.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/styles-tables.css"> -->

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/relatorio-contribuicao.css">

    <script src="../assets/js/scripts.js" defer></script>
</head>

<body class="<?= $pageClass ?>">
    <main>

        <h2>Relatório Global de Contribuições</h2>
        <?php if (count($contribuicoes) > 0): ?>
            <div class="container">
                <table>
                    <caption>
                        <p><strong>Mês:</strong> <?= str_pad($mes, 2, '0', STR_PAD_LEFT); ?> / <?= $ano; ?></p>
                    </caption>
                    <thead>
                        <tr>
                            <th>Contribuição</th>
                            <th>Carlos Alberto Silva</th>
                            <th>Iriluce Oliveira Silva</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contribuicoes as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['nome']); ?></td>
                                <td> <?php echo formatarValor(($c['valor'] / 2)); ?></td>
                                <td> <?php echo formatarValor(($c['valor'] / 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="subTotal">
                            <td>Total</td>
                            <td> <?php echo formatarValor(($total / 2)); ?></td>
                            <td> <?php echo formatarValor(($total / 2)); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>

                        <tr>
                            <td colspan="3">Total Geral:  <?php echo formatarValor($total); ?></td>
                        </tr>
                    </tfoot>
                </table>
                <button title="Imprimir Relatório" class="no-print button-icon" onclick="window.print()">🖨️</button>
                <button title="Fechar Relatório" class="no-print button-icon" onclick="fecharRelatorio()">❌</button>
            </div>
        <?php else: ?>
            <p>Nenhuma contribuição encontrada para o período selecionado.</p>
        <?php endif; ?>
    </main>
    <?php include '../includes/footer.php' ?>
</body>

</html>