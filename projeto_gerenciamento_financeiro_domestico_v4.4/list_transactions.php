<?php
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
    <link rel="stylesheet" href="media_queries.css">

    <script src="scripts.js" defer></script>


    <style>
        /* Estilo mobile-first: telas pequenas */
        .form-filtro {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            /* font-family: monospace; */
        }

        .form-filtro label {
            font-weight: bold;
            /* color: #444; */
        }

        .form-filtro input,
        .form-filtro select,
        .form-filtro button {
            padding: 8px;
            font-size: 1em;
            border-radius: 4px;
            border: 1px solid #bbb;
            /* font-family: monospace; */
        }

        /* .form-filtro button { */
            /* background-color: #f1c40f; */
            /* border: none; */
            /* color: #222; */
            /* font-weight: bold; */
            /* cursor: pointer; */
            /* transition: background-color 0.3s; */
        /* } */

        /* .form-filtro button:hover {
            background-color: #f39c12;
        } */

        /* Telas maiores: layout em linha */
        /* @media (min-width: 768px) {

            .form-filtro {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: flex-end;
                justify-content: center;
                gap: 15px;
            }

            .form-filtro label {
                margin-right: 5px;
            }

            .form-filtro input,
            .form-filtro select {
                width: auto;
            }

            .form-filtro button {
                height: 38px;
                padding: 0 20px;
            }
        } */
        @media (min-width: 768px) {
            .form-filtro {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center; /* Alinha ao centro horizontalmente */
                gap: 15px;
            }

            .form-filtro label {
                margin-right: 5px;
            }

            .form-filtro input,
            .form-filtro select {
                width: auto;
            }

            .form-filtro button {
                height: 38px;
                padding: 0 20px;
                margin-bottom: 15px;
            }
        }
    </style>
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
    <?php include 'footer.php'; ?>

    <script>
        window.onload = document.getElementById('tipo').focus();
    </script>
</body>

</html>