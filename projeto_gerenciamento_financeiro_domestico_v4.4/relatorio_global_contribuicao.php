<?php
include 'db_connection.php';
$pageClass = 'sem-menu';
include 'header.php';

$mes = $_GET['mes'] ?? date('m');
$ano = $_GET['ano'] ?? date('Y');

$sql = "SELECT nome, tipo, descricao, valor, data_vencimento 
        FROM transacoes 
        WHERE nome IN ('Dízimo', 'Oferta') AND mes = :mes AND ano = :ano ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
$stmt->execute();
$contribuicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = array_sum(array_column($contribuicoes, 'valor'));
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatório Global de Contribuições</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style_relatorio_contribuicao.css">
    <link rel="stylesheet" href="styles-tables.css">
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
                                <td>R$ <?= number_format(($c['valor'] / 2), 2, ',', '.'); ?></td>
                                <td>R$ <?= number_format(($c['valor'] / 2), 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="subTotal">
                            <td>Total</td>
                            <td>R$ <?= number_format(($total / 2), 2, ',', '.'); ?></td>
                            <td>R$ <?= number_format(($total / 2), 2, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>

                        <tr>
                            <td colspan="3">Total Geral: R$ <?= number_format($total, 2, ',', '.'); ?></td>
                        </tr>
                    </tfoot>
                </table>
                <button title="Imprimir Relatório" class="no-print btn" onclick="window.print()">🖨️</button>
                <button title="Fechar Relatório" class="btn" onclick="fecharRelatorio()">❌</button>
            </div>
        <?php else: ?>
            <p>Nenhuma contribuição encontrada para o período selecionado.</p>
        <?php endif; ?>
    </main>
    <?php include 'footer.php' ?>

    <script>
        function fecharRelatorio() {
            window.close();
        }
    </script>
</body>

</html>