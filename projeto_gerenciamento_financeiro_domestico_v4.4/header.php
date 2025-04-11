<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-header.css">
    <link rel="stylesheet" href="style_media_queries.css">

    <script src="https://kit.fontawesome.com/ba16269ee8.js" crossorigin="anonymous"></script>
    <script src="scripts.js" defer></script>

</head>

<body onresize="mudouTamanho()">
    <header>
        <div class="logo">
            <h1><i>GeFinD</i></h1>
        </div>
        <p><strong>Gerenciamento Financeiro Doméstico</strong></p>

        <i class="fa-solid fa-bars menu-toggle" onclick="clickMenu()" id="menu"></i>

        <nav id="itens">
            <ul id="nav-links">
                <li><a href="index.php">Início</a></li>
                <li class="dropdown">
                    <a href="#">Nova Transação ▾</a>
                    <ul class="dropdown-menu">
                        <li><a href="form_add_transaction.php">Nova Transação</a></li>
                        <li><a href="dizimos.php">Dízimos e Ofertas</a></li>
                    </ul>
                </li>
                <li><a href="list_transactions.php">Lista de Transações</a></li>
                <li><a href="report_transactions.php">Relatório</a></li>
                <li><a href="lista-de-compras.php">Lista de compras</a></li>
            </ul>
        </nav>
    </header>
</body>

</html>