<?php
require 'functions.php';
session_start();
protegerPagina();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Recupera informações da sessão
$usuario = $_SESSION['usuario'];
$tipo = $_SESSION['tipo'];
?>

<h2>Bem-vindo, <?php echo htmlspecialchars($usuario); ?>!</h2>
<p>Tipo de usuário: <strong><?php echo $tipo; ?></strong></p>

<ul>
    <?php if ($tipo === 'admin'): ?>
        <li><a href="painel_admin.php">Painel Administrativo</a></li>
        <li><a href="dashboard.php">Dashboard</a></li>
    <?php endif; ?>
    <li><a href="perfil.php">Meu Perfil</a></li>
    <li><a href="logout.php">Sair</a></li>
</ul>
