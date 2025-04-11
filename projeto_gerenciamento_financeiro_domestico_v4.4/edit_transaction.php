<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM transacoes WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $transacao = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $data_vencimento = $_POST['data_vencimento'];
    $valor = $_POST['valor'];
    $tipo = $_POST['tipo'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE transacoes SET nome = :nome, data_vencimento = :data_vencimento, valor = :valor, tipo = :tipo, forma_pagamento = :forma_pagamento, descricao = :descricao WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':data_vencimento', $data_vencimento);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':forma_pagamento', $forma_pagamento);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: list_transactions.php');
        exit;
    } else {
        echo "Erro ao atualizar a transação.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Transação</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-form.css">
    <link rel="stylesheet" href="media_queries.css">
     
    <script src="scripts.js" defer></script>
    <script src="script-carrega-opcoes.js" defer></script>
    <script src="script-carrega-nome.js" defer></script>
</head>

<body onresize="mudouTamanho()">
    <h2>Editar Transação</h2>
    <form action="edit_transaction.php" method="POST" class="form-geral">

        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required>
            <option value="receita" <?php if ($transacao['tipo'] === 'receita') echo 'selected'; ?>>Receita</option>
            <option value="despesa" <?php if ($transacao['tipo'] === 'despesa') echo 'selected'; ?>>Despesa</option>
        </select>

        <input type="hidden" name="id" value="<?php echo $transacao['id']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($transacao['nome']); ?>" required>

        <label for="data_vencimento">Data de Vencimento:</label>
        <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo $transacao['data_vencimento']; ?>" required>

        <label for="valor">Valor:</label>
        <input type="number" step="0.01" id="valor" name="valor" value="<?php echo $transacao['valor']; ?>" required>



        <label for="forma_pagamento">Forma de Pagamento:</label>
        <select id="forma_pagamento" name="forma_pagamento" required>
            <option value="Boleto Bancário" <?php if ($transacao['forma_pagamento'] === 'Boleto Bancário') echo 'selected'; ?>>Boleto Bancário</option>

            <option value="Cheque" <?php if ($transacao['forma_pagamento'] === 'Cheque') echo 'selected'; ?>>Cheque</option>

            <option value="Crédito em Conta" <?php if ($transacao['forma_pagamento'] === 'Crédito em Conta') echo 'selected'; ?>>Crédito em Conta</option>

            <option value="Débito em Conta" <?php if ($transacao['forma_pagamento'] === 'Débito em Conta') echo 'selected'; ?>>Débito em Conta</option>

            <option value="Débito Automático" <?php if ($transacao['forma_pagamento'] === 'Débito Automático') echo 'selected'; ?>>Débito Automático</option>

            <option value="PIX" <?php if ($transacao['forma_pagamento'] === 'PIX') echo 'selected'; ?>>PIX</option>
        </select>

        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" value="<?php echo htmlspecialchars($transacao['descricao']); ?>" maxlength="45">

        <button type="submit">Atualizar</button>
    </form>
</body>

</html>