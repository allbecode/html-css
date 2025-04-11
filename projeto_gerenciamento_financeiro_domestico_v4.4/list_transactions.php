<?php
include 'db_connection.php';
include 'header.php';

$anoAtual = date('Y');

$sql = "SELECT * FROM transacoes ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Transações</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="styles-tables.css">
    <link rel="stylesheet" href="media_queries.css">

    <script src="scripts.js" defer></script>
</head>

<body>
    <main>
        <h2>Lista de Receitas e Despesas</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Forma de Pagamento</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacoes as $transacao): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($transacao['nome']); ?>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($transacao['data_vencimento'])); ?>
                            </td>
                            <td>
                                R$ <?php echo number_format($transacao['valor'], 2, ',', '.'); ?>
                            </td>
                            <td>
                                <?php echo ucfirst($transacao['tipo']); ?>
                            </td>
                            <td>
                                <?php echo $transacao['forma_pagamento']; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($transacao['descricao']); ?>
                            </td>
                            <td class="<?php echo $transacao['pago'] ? 'status-pago' : 'status-nao-pago'; ?>">
                                <?php echo $transacao['pago'] ? '✔' : '✖'; ?>
                            </td>
                            <td>
                                <a class="button edit" href="edit_transaction.php?id=<?php echo $transacao['id']; ?>">Editar</a>
                                <a class="button delete" href="delete_transaction.php?id=<?php echo $transacao['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include 'footer.php';?>
</body>

</html>