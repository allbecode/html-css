<?php
include 'db_connection.php';
include 'header.php';

if (!isset($_GET['mes']) || !isset($_GET['ano']) || !isset($_GET['valor_dizimo'])) {
    die("Dados insuficientes para gerar o relatório.");
}

$mes = $_GET['mes'];
$ano = $_GET['ano'];
$nome = $_GET['nome'];
$valor = $_GET['valor_dizimo'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Dízimo</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style_relatorio_dizimo.css">
    <link rel="stylesheet" href="styles-tables.css">

</head>
<body>

<main>
    <h2>Relatório de Dízimos/Ofertas</h2>
    <div class="container">
        <p class="info"><strong>Mês:</strong> <?= htmlspecialchars($mes) ?>/<?= htmlspecialchars($ano) ?></p>
        <table>
            <thead>
                <tr>
                    <th>Contribuição</th>
                    <th>Carlos Alberto Silva</th>
                    <th>Iriluce Oliveira Silva</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><p class="info"><?= htmlspecialchars($nome); ?></p></td>
                    <td><p class="info">R$ <?= number_format(($valor/2), 2, ',', '.') ?></p></td>
                    <td><p class="info">R$ <?= number_format(($valor/2), 2, ',', '.') ?></p></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><p class="info">Valor do Dízimo: R$ <?= number_format($valor, 2, ',', '.') ?></p></td>
                </tr>
            </tfoot>
        </table>

        <button onclick="window.print()">Imprimir Relatório</button>
    </div>
</main>

</body>
</html>
