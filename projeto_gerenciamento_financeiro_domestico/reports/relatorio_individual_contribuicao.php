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
    <!-- <title>Relatório Individual de Contribuições</title> -->

    <link rel="stylesheet" href="../assets/css/styles-principal.css">
    <link rel="stylesheet" href="../assets/css/style_relatorio_contribuicao.css">
    <link rel="stylesheet" href="../assets/css/styles-tables.css">

    <script src="../assets/js/scripts.js" defer></script>

</head>

<body class="<?= $pageClass ?>">

    <main>
        <h2>Relatório de Dízimos/Ofertas</h2>
        <div class="container">
            <p class="info"><strong>Mês:</strong> <?= str_pad($mes, 2, '0', STR_PAD_LEFT); ?> / <?= htmlspecialchars($ano) ?></p>
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
                            <p class="info"><?= htmlspecialchars($nome); ?></p>
                        </td>
                        <td>
                            <p class="info"> <?php echo formatarValor(($valor / 2)) ?></p>
                        </td>
                        <td>
                            <p class="info"> <?php echo formatarValor(($valor / 2)) ?></p>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <p class="info">Valor do Dízimo: R$ <?php echo formatarValor($valor) ?></p>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <button title="Imprimir Relatório" class="btn" onclick="window.print()">🖨️</button>
            <button title="Fechar Relatório" class="btn" onclick="fecharRelatorio()">❌</button>
        </div>
    </main>
    <?php include '../includes/footer.php' ?>
</body>

</html>