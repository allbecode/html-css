<?php
// require_once '../controllers/controller_relatorio_individual_contribuicao.php';
// require_once '../includes/functions.php';
// include '../includes/header.php';
?>

<!-- <!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Individual de Contribuições</title>

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/relatorio-contribuicao.css">

     <script src="../assets/js/utils.js" defer></script>
    <script src="../assets/js/scripts.js" defer></script>

</head>

<body class="<?= $pageClass ?>">

    <main>
        <h2>Relatório de Dízimos/Ofertas</h2>
        <div class="container">
            <p><strong>Mês:</strong> <?= str_pad($mes, 2, '0', STR_PAD_LEFT); ?> / <?= htmlspecialchars($ano) ?></p>
            <table>
                <caption>
                    <p><strong><?= htmlspecialchars($nome); ?>:</strong> <?= htmlspecialchars($descricao); ?></p>
                </caption>
                <thead>
                    <tr>
                        <th>Contribuição</th>
                        <th>Carlos Alberto Silva</th>
                        <th>Iriluce Oliveira Silva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p><?= htmlspecialchars($nome); ?></p>
                        </td>
                        <td>
                            <p> <?php echo formatarValor(($valor / 2)) ?></p>
                        </td>
                        <td>
                            <p> <?php echo formatarValor(($valor / 2)) ?></p>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <p>Valor do Dízimo: <?php echo formatarValor($valor) ?></p>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <button title="Imprimir Relatório" class=" no-print button-icon" onclick=imprimirLista()>🖨️</button>
            <button title="Fechar Relatório" class="no-print button-icon" onclick="fecharRelatorio()">❌</button>
        </div>
    </main>
    <?php include '../includes/footer.php' ?>
</body>

</html> -->

<?php
require_once '../controllers/controller_relatorio_individual_contribuicao.php';
require_once '../includes/functions.php';
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/relatorio-contribuicao.css">

    <script src="../assets/js/utils.js" defer></script>
    <script src="../assets/js/scripts.js" defer></script>
</head>

<body class="<?= $pageClass ?>">
    <main>
        <h2>Relatório de Dízimos/Ofertas</h2>
        <div class="container">
            <p><strong>Mês:</strong> <?= str_pad($mes, 2, '0', STR_PAD_LEFT); ?> / <?= htmlspecialchars($ano); ?></p>
            <table>
                <caption>
                    <p><strong><?= htmlspecialchars($nome); ?>:</strong> <?= htmlspecialchars($descricao); ?></p>
                </caption>
                <thead>
                    <tr>
                        <th>Contribuição</th>
                        <th><?= htmlspecialchars($nomeUsuario); ?></th>
                        <?php if ($nomeDependente): ?>
                            <th><?= htmlspecialchars($nomeDependente); ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($nome); ?></td>
                        <td><?= formatarValor($valor / 2); ?></td>
                        <?php if ($nomeDependente): ?>
                            <td><?= formatarValor($valor / 2); ?></td>
                        <?php endif; ?>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="<?= $nomeDependente ? '3' : '2' ?>">
                            Valor do Dízimo: <?= formatarValor($valor); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <button title="Imprimir Relatório" class="no-print button-icon" onclick="imprimirLista()">🖨️</button>
            <button title="Fechar Relatório" class="no-print button-icon" onclick="fecharRelatorio()">❌</button>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
