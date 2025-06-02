<?php
include 'db_connection.php';
$pageClass = 'sem-menu';
include 'header.php';

if (!isset($_GET['mes']) || !isset($_GET['ano']) || !isset($_GET['valor_dizimo'])) {
    die("Dados insuficientes para gerar o relatório.");
}

$mes = $_GET['mes'];
$ano = $_GET['ano'];
$nome = $_GET['nome'];
$valor = $_GET['valor_dizimo'];
$descricao = $_GET['descricao'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Dízimo</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style_relatorio_contribuicao.css">
    <link rel="stylesheet" href="styles-tables.css">

    <script>
        // window.onload = function () {
        // window.print(); // Chama o assistente de impressão assim que a página carrega
        // };
    </script>
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
                            <p class="info">R$ <?= number_format(($valor / 2), 2, ',', '.') ?></p>
                        </td>
                        <td>
                            <p class="info">R$ <?= number_format(($valor / 2), 2, ',', '.') ?></p>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <p class="info">Valor do Dízimo: R$ <?= number_format($valor, 2, ',', '.') ?></p>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <button title="Imprimir Relatório" class="btn" onclick="window.print()">🖨️</button>
            <button title="Fechar Relatório" class="btn" onclick="fecharRelatorio()">❌</button>
        </div>
    </main>
    <?php include 'footer.php' ?>

    <script>
        function fecharRelatorio() {
            window.close();
        }
    </script>

</body>

</html>