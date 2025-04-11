<?php
include "db_connection.php";
include 'header.php';

// Função para adicionar uma transação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $data_vencimento = $_POST['data_vencimento'];
    $valor = $_POST['valor'];
    $tipo = $_POST['tipo'];
    $forma_pagamento = $_POST['forma_pagamento'];
    $descricao = $_POST['descricao'];
    $ano = date('Y', strtotime($data_vencimento));
    $mes = date('m', strtotime($data_vencimento));

    // Se for uma receita, marcar como "paga" automaticamente
    $pago = ($tipo === 'receita') ? 1 : 0;

    $sql = "INSERT INTO transacoes (nome, data_vencimento, valor, tipo, forma_pagamento, descricao, ano, mes, pago) VALUES (:nome, :data_vencimento, :valor, :tipo, :forma_pagamento, :descricao, :ano, :mes, :pago)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':data_vencimento', $data_vencimento);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':forma_pagamento', $forma_pagamento);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':ano', $ano);
    $stmt->bindParam(':mes', $mes);
    $stmt->bindParam(':pago', $pago);

    if ($stmt->execute()) {
        $msg = true;
    } else {
        $msg = false;
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="media_queries.css">
    
    <script src="scripts.js" defer></script>
</head>

<body>
    <?php
    if ($msg == true) {
        echo "<div class='execute confirm'><h3>Transação adicionada com sucesso!</h3></div>";
    } else {
        echo "<div class='execute error'><h3>Erro ao adicionar a transação</h3></div>";
    }
    ?>
</body>

</html>