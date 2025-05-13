<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-form.css">
    <link rel="stylesheet" href="style_relatorio_contribuicao.css">
    <link rel="stylesheet" href="media_queries.css">

    <script src="scripts.js" defer></script>
    <script src="script-carrega-opcoes.js" defer></script>
    <script src="script-carrega-nome.js" defer></script>
    <script src="script-ajax.js" defer></script>

    <style>
        /* Mobile-first: elementos empilhados */
        .container-flex {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;

        }

        .form-geral {
            flex: 1;
        }

        /* Estilo da listagem de transações */
        .lista-transacoes-dia {
            flex: 1;
            background-color: #fffacd;
            /* amarelo-claro (lemon chiffon) */
            border: 1px solid #e6e600;
            padding: 15px;
            font-family: monospace;
            color: #333;
            text-align: center;
            max-width: 80%;
            overflow-x: auto;
            max-height: 60vh;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin: 0 auto;

        }

        .lista-transacoes-dia h3 {
            margin-top: 0;
            text-align: center;
            font-size: 1.1rem;
            border-bottom: 2px dashed #999;
            padding-bottom: 5px;
        }

        .lista-transacoes-dia th {
            padding: 10px 0px;
            border-bottom: 2px dashed #999;
        }

        .lista-transacoes-dia tr {
            list-style-type: none;
            padding-left: 0;
            margin: 10px 0 0 0;
        }

        .lista-transacoes-dia td {
            padding: 5px 0;
            border-bottom: 1.5px dashed #999;
        }

        /* Layout horizontal a partir de 768px */
        @media (min-width: 768px) {
            .container-flex {
                flex-direction: row;
                align-items: flex-start;
                justify-content: space-between;

                width: 800px;
                margin: auto;
            }

            .form-geral,
            .lista-transacoes-dia {
                width: 48%;
            }

            .lista-transacoes-dia {
                max-height: 60vh;
                overflow-y: auto;
            }
        }
    </style>
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