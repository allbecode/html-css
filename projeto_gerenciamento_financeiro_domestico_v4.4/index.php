<?php
include 'db_connection.php';
include 'header.php';

$dataAtual = date('Y-m-d');
$diaAtual = date('d');
$mesAtual = date('m');
$anoAtual = date('Y');

// Buscar despesas vencidas (não pagas)
$sqlVencidas = "SELECT * FROM transacoes WHERE tipo = 'despesa' AND data_vencimento < :dataAtual AND mes = :mesAtual AND ano = :anoAtual AND pago = 0";
$stmt = $pdo->prepare($sqlVencidas);
$stmt->bindParam(':dataAtual', $dataAtual);
$stmt->bindParam(':mesAtual', $mesAtual);
$stmt->bindParam(':anoAtual', $anoAtual);
$stmt->execute();
$despesasVencidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar despesas com vencimento hoje (não pagas)
$sqlHoje = "SELECT * FROM transacoes WHERE tipo = 'despesa' AND data_vencimento = :dataAtual AND mes = :mesAtual AND ano = :anoAtual AND pago = 0";
$stmt = $pdo->prepare($sqlHoje);
$stmt->bindParam(':dataAtual', $dataAtual);
$stmt->bindParam(':mesAtual', $mesAtual);
$stmt->bindParam(':anoAtual', $anoAtual);
$stmt->execute();
$despesasHoje = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcular saldo do mês atual
$sqlSaldo = "SELECT 
                SUM(CASE WHEN tipo = 'receita' THEN valor ELSE 0 END) AS totalReceitas,
                SUM(CASE WHEN tipo = 'despesa' THEN valor ELSE 0 END) AS totalDespesas
            FROM transacoes 
            WHERE mes = :mesAtual AND ano = :anoAtual";
$stmt = $pdo->prepare($sqlSaldo);
$stmt->bindParam(':mesAtual', $mesAtual);
$stmt->bindParam(':anoAtual', $anoAtual);
$stmt->execute();
$saldo = $stmt->fetch(PDO::FETCH_ASSOC);

$saldoAtual = $saldo['totalReceitas'] - $saldo['totalDespesas'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="styles-tables.css">
    <link rel="stylesheet" href="media_queries.css">

    <script src="scripts.js" defer></script>

</head>

<body>
    
    <main>
        <h3>Despesas Vencidas (Mês Atual: <?php echo "$mesAtual/$anoAtual"?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>Vencimento</th>
                    <th>Forma de Pgto</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($despesasVencidas)): ?>
                    <?php foreach ($despesasVencidas as $despesa): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($despesa['data_vencimento'])); ?></td>
                            <td><?php echo $despesa['forma_pagamento']; ?></td>
                            <td><?php echo htmlspecialchars($despesa['nome']); ?></td>
                            <td>R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></td>
                            <td><button onclick="marcarComoPago(<?php echo $despesa['id']; ?>)">Marcar como Paga</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma despesa vencida este mês.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <h3>Despesas com Vencimento Hoje (<?php echo " $diaAtual/$mesAtual "?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>Vencimento</th>
                    <th>Forma de Pgto</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($despesasHoje)): ?>
                    <?php foreach ($despesasHoje as $despesa): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($despesa['data_vencimento'])); ?></td>
                            <td><?php echo $despesa['forma_pagamento']; ?></td>
                            <td><?php echo htmlspecialchars($despesa['nome']); ?></td>
                            <td>R$ <?php echo number_format($despesa['valor'], 2, ',', '.'); ?></td>
                            <td><button onclick="marcarComoPago(<?php echo $despesa['id']; ?>)">Marcar como Paga</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma despesa com vencimento hoje.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="saldo">
            <h3>Saldo do Mês Atual: R$ <?php echo number_format($saldoAtual, 2, ',', '.'); ?></h3>
        </div>
    </main>
    <?php include 'footer.php';?>
</body>

</html>