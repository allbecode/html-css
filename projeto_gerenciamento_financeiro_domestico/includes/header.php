<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>

    <!-- <link rel="stylesheet" href="../assets/css/styles-principal.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/style-header.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/style_media_queries.css"> -->

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-header.css">

    <script src="https://kit.fontawesome.com/ba16269ee8.js" crossorigin="anonymous"></script>
    <script src="../assets/js/scripts.js" defer></script>

</head>

<body onresize="mudouTamanho()">
    <header>
        <div class="logo">
            <h1><a href="../pages/index.php"><i>GeFinD</i></a></h1>
        </div>
        <p><strong>Gerenciamento Financeiro Doméstico</strong></p>

        <i class="fa-solid fa-bars menu-toggle" onclick="clickMenu()" id="menu"></i>

        <nav id="itens">
            <ul id="nav-links">
                <li><a href="../pages/index.php">Início</a></li>
                <li class="dropdown">
                    <a href="#">Nova Transação ▾</a>
                    <ul class="dropdown-menu">
                        <li><a href="../pages/form_add_transaction.php">Adicionar Transação</a></li>
                        <li><a href="../pages/add_contribuicoes.php">Contribuições</a></li>
                    </ul>
                </li>
                <li><a href="../pages/list_transactions.php">Lista de Transações</a></li>
                <li><a href="../reports/report_transactions.php">Relatório</a></li>
                <li><a href="../pages/lista-de-compras.php">Lista de compras</a></li>
            </ul>
        </nav>
    </header>
</body>

</html>