<?php
include 'db_connection.php';
include 'header.php';

$receitas = [];
$despesas = [];
$totalReceitas = 0;
$totalDespesas = 0;
$saldoFinal = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];

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
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-form.css">
    <link rel="stylesheet" href="styles-tables.css">
    <link rel="stylesheet" href="style_form_contribuicao.css">
    <link rel="stylesheet" href="style_form_dizimo.css">
    <link rel="stylesheet" href="media_queries.css">

    <script src="scripts.js" defer></script>
</head>

<body>
    <main>
        <h2>Consultar Transações</h2>
        <form action="report_transactions.php" method="POST" class="form-contribuicao">

            <div class="mes">
                <label for="mes">Mês:</label>
                <select id="mes" name="mes" required>
                    <option value="">Selecione um mês...</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="ano">
                <label for="ano">Ano:</label>
                <input type="number" id="ano" name="ano" value="<?php echo date('Y'); ?>" required>
            </div>

            <button type="submit">Consultar</button>
        </form>


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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
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
    <?php include 'footer.php';?>
</body>

</html>