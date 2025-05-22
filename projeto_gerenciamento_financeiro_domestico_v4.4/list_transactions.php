<?php

// Falta:
// 
// 1- Eliminar o código que faz com que a tecla enter edite a linha;
// **- Eliminar os comentário indevidos;


include 'db_connection.php';
include 'header.php';

$anoAtual = date('Y');

$sql = "SELECT * FROM transacoes ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$condicoes = [];
$params = [];

if (!empty($_GET['tipo'])) {
    $condicoes[] = "tipo = :tipo";
    $params[':tipo'] = $_GET['tipo'];
}

if (!empty($_GET['ano'])) {
    $condicoes[] = "YEAR(data_vencimento) = :ano";
    $params[':ano'] = $_GET['ano'];
}

if (!empty($_GET['mes'])) {
    $condicoes[] = "MONTH(data_vencimento) = :mes";
    $params[':mes'] = $_GET['mes'];
}

if (!empty($_GET['nome'])) {
    $condicoes[] = "nome LIKE :nome";
    $params[':nome'] = '%' . $_GET['nome'] . '%';
}

if (isset($_GET['pago']) && $_GET['pago'] !== '') {
    $condicoes[] = "pago = :pago";
    $params[':pago'] = $_GET['pago'];
}

$whereSQL = count($condicoes) ? 'WHERE ' . implode(' AND ', $condicoes) : '';

$sql = "SELECT * FROM transacoes $whereSQL ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
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
    <link rel="stylesheet" href="style-lista-transacoes.css">
    <link rel="stylesheet" href="media_queries.css">

    <script src="scripts.js" defer></script>
    <script src="script-lista-transacoes.js" defer></script>

</head>

<body>
    <main>
        <h2>Lista de Receitas e Despesas</h2>
        <div class="container-form">
            <form method="GET" class="form-filtro">
                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="">Todos</option>
                    <option value="receita" <?= $_GET['tipo'] === 'receita' ? 'selected' : '' ?>>Receita</option>
                    <option value="despesa" <?= $_GET['tipo'] === 'despesa' ? 'selected' : '' ?>>Despesa</option>
                </select>
                <label for="ano">Ano:</label>
                <input type="number" name="ano" id="ano" value="<?= $_GET['ano'] ?? $anoAtual ?>">
                <label for="mes">Mês:</label>
                <input type="number" name="mes" id="mes" min="1" max="12" value="<?= $_GET['mes'] ?? '' ?>">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= $_GET['nome'] ?? '' ?>">
                <label for="pago">Status:</label>
                <select name="pago" id="pago">
                    <option value="">Todos</option>
                    <option value="1" <?= $_GET['pago'] === '1' ? 'selected' : '' ?>>Pago</option>
                    <option value="0" <?= $_GET['pago'] === '0' ? 'selected' : '' ?>>Não Pago</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>
        </div>


        <div class="table-container">
            <div id="mensagem-edicao" class="mensagem-flutuante" style="display: none;">
                Clique duas vezes sobre a linha para editar as informações.
            </div>
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
                        <tr data-id="<?= $transacao['id'] ?>" class="transacao-linha">
                            <td data-field="nome">
                                <?php echo htmlspecialchars($transacao['nome']); ?>
                            </td>
                            <td data-field="data_vencimento">
                                <?php echo date('d/m/Y', strtotime($transacao['data_vencimento'])); ?>
                            </td>
                            <td data-field="valor">
                                R$ <?php echo number_format($transacao['valor'], 2, ',', '.'); ?>
                            </td>
                            <td data-field="tipo">
                                <?php echo ucfirst($transacao['tipo']); ?>
                            </td>
                            <td data-field="forma_pagamento">
                                <?php echo $transacao['forma_pagamento']; ?>
                            </td>
                            <td data-field="descricao">
                                <?php echo htmlspecialchars($transacao['descricao']); ?>
                            </td>
                            <td data-field="pago" class="<?php echo $transacao['pago'] ? 'status-pago' : 'status-nao-pago'; ?>">
                                <?php echo $transacao['pago'] ? '✔' : '✖'; ?>
                            </td>
                            <td class="acoes">
                                <div class="acoes-container">
                                    <button class="button delete visible" data-id="<?= $transacao['id']; ?>">Excluir</button>

                                    <button class="button salvar hidden">Salvar</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include 'footer.php'; ?>

</body>

</html>