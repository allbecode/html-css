<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/layout.php';
require_once '../includes/usuario.php';
require_once '../includes/functions.php';

protegerPagina();

$username = $_SESSION['usuario'];
$tipo = $_SESSION['tipo'];
$primeiro_nome = explode(' ', trim($_SESSION['nome'] ?? $_SESSION['usuario']))[0];
$bemVindo = (str_ends_with(strtolower($primeiro_nome),'a')) ? 'Bem vinda' : 'Bem vindo';
template_header("Boas Vindas");
?>

<h2><?= $bemVindo ?>, <?php echo htmlspecialchars($primeiro_nome); ?>!</h2>
<p>Tipo de usuário: <strong><?php echo htmlspecialchars($tipo) ?></strong></p>


<ul>
    <li><a href="perfil.php">Meu Perfil</a></li>
    <?php if ($tipo === 'admin'): ?>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="painel_admin.php">Painel Administrativo</a></li>
    <?php endif; ?>
    <li><a href="logout.php">Sair</a></li>
</ul>

<?php template_footer(); ?>
