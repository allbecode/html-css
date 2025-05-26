<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-form.css">
    <link rel="stylesheet" href="style-form-add-transaction.css">

    <script src="scripts.js" defer></script>
    <script src="script-carrega-opcoes.js" defer></script>
    <script src="script-carrega-nome.js" defer></script>
    <script src="script-ajax.js" defer></script>
    <script src="script-auto-focus-form.js" defer></script>

</head>

<body>
    <main>
        <h2>Adicionar Receita/Despesa</h2>
        <div class="container-flex">

            <form id="form-transacao" action="add_transaction.php" method="POST" class="form-geral" data-origem="transacao">
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Selecione um tipo</option>
                    <option value="receita">Receita</option>
                    <option value="despesa">Despesa</option>
                </select>
                <label for="nome">Nome:</label>
                <select name="nome" id="nome">
                    <option value="">Selecione o tipo primeiro</option>
                </select>
                <label for="data_vencimento">Data de Vencimento:</label>
                <input type="date" id="data_vencimento" name="data_vencimento" required>
                <label for="valor">Valor:</label>
                <input type="number" step="0.01" id="valor" name="valor" required>
                <label for="forma_pagamento">Forma de Pagamento:</label>
                <select id="forma_pagamento" name="forma_pagamento" required>
                    <option value="">Selecione...</option>
                </select>
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" maxlength="45">
                <button type="submit">Adicionar</button>
            </form>
            <div class="lista-transacoes-dia">

                <h3>Transações realizadas hoje</h3>
                <div id="transacoes-do-dia" class="tabela-transacoes-dia">
                    <!-- Será preenchido via AJAX -->
                </div>
            </div>
        </div>

    </main>
    <?php include 'footer.php'; ?>
</body>

</html>