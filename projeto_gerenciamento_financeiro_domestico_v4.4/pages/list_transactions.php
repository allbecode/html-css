<?php
require_once '../controllers/controller_list_transactions.php';
require_once '../includes/functions.php';
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Lista de Transações</title> -->

    <link rel="stylesheet" href="../assets/css/styles-principal.css">
    <link rel="stylesheet" href="../assets/css/styles-tables.css">
    <link rel="stylesheet" href="../assets/css/style-lista-transacoes.css">
    <link rel="stylesheet" href="../assets/css/media_queries.css">
    <link rel="stylesheet" href="../assets/css/style-report-transactions.css">

    <script src="../assets/js/scripts.js" defer></script>
    <script src="../assets/js/script-lista-transacoes.js" defer></script>

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

                    <?php if (count($transacoes) > 0): ?>

                        <?php foreach ($transacoes as $transacao): ?>
                            <tr data-id="<?= $transacao['id'] ?>" class="transacao-linha">
                                <td data-field="nome">
                                    <?php echo htmlspecialchars($transacao['nome']); ?>
                                </td>
                                <td data-field="data_vencimento">
                                    <?php echo formatarDataBr($transacao['data_vencimento']); ?>
                                </td>
                                <td data-field="valor">
                                    <?php echo formatarValor($transacao['valor']); ?>
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
                                        <button title="Excluir" class="button delete visible" data-id="<?= $transacao['id']; ?>">🗑️</button>

                                        <button title="Salvar" class="button salvar hidden">💾</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">
                                <div class="mensagem-sem-dados">
                                    Nenhuma transação encontrada com <strong>o(s) parâmetrod(s)</strong> selecionado(s).
                                </div>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>

</body>

</html>