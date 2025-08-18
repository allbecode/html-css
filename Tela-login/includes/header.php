<?php 
require_once '../acsses_control/includes/functions.php';

require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/layout.php';
require_once '../acsses_control/includes/usuario.php';
require_once '../acsses_control/includes/functions.php';

verificaUsuarioLogado();

$username = $_SESSION['usuario'];
$tipo = $_SESSION['tipo'];
$primeiro_nome = explode(' ', trim($_SESSION['nome'] ?? $_SESSION['usuario']))[0];
$bemVindo = (str_ends_with(strtolower($primeiro_nome),'a')) ? 'Bem vinda' : 'Bem vindo';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador Financeiro</title>

    <link rel="stylesheet" href="../assets/css/segmentation/layout-header.css">

    <script src="../assets/js/script-header.js" defer></script>
    <script src="https://kit.fontawesome.com/ba16269ee8.js" crossorigin="anonymous"></script>
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
                <!-- Sub-menu usuário -->
                <li class="dropdown">
                    <!-- Futuro avatar -->
                        <i class="fa-solid fa-user" id="menu-user"></i>
                    <ul class="dropdown-menu">
                        <br><p>Olá <strong><?php echo htmlspecialchars($primeiro_nome); ?></strong></p><br> <!-- Carregar o nome do usuário logado -->
                        <li><a href="../acsses_control/pages/perfil.php">Meu Perfil</a></li>
                        <li><a href="../acsses_control/pages/logout.php">Sair</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>
</body>

</html>