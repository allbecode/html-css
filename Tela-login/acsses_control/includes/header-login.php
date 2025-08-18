<?php

require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/layout.php';
require_once '../includes/usuario.php';
require_once '../includes/functions.php';

verificaUsuarioLogado();

$username = $_SESSION['usuario'];
$tipo = $_SESSION['tipo'];
$primeiro_nome = explode(' ', trim($_SESSION['nome'] ?? $_SESSION['usuario']))[0];
$bemVindo = (str_ends_with(strtolower($primeiro_nome), 'a')) ? 'Bem vinda' : 'Bem vindo';

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFinD - Admin</title>

    <link rel="stylesheet" href="../../assets/css/segmentation/layout-header.css">

    <script src="../../assets/js/script-header.js" defer></script>
    <script src="https://kit.fontawesome.com/ba16269ee8.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <div>
            <h1><a href="../pages/index.php"><i>GeFinD</i></a></h1>
        </div>
        <div>
            <!-- <p><strong><?= $bemVindo ?>, <?php echo htmlspecialchars($primeiro_nome); ?>!</strong></p> -->
            <!-- <p>Tipo de usuário: <strong><?php echo htmlspecialchars($tipo) ?></strong></p> -->
        </div>
        <!-- <p><strong>GeFinD - Administrativo</strong></p> -->
         <i class="fa-solid fa-bars menu-toggle" onclick="clickMenu()" id="menu"></i>
        <nav id="itens">
            <ul>
                <li><a href="../../index.php">Início</a></li>
                <?php if ($tipo === 'admin'): ?>
                    <li><a href="perfil.php">Meu Perfil</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="painel_admin.php">Painel Administrativo</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
</body>

</html>