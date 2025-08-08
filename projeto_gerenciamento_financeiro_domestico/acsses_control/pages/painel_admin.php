<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/layout.php';
require_once '../includes/log_functions.php';
require_once '../includes/admin_functions.php';

protegerPagina();
exigirAdmin();

$pdo = conectar();
$usuarios = buscarUsuariosComFiltro($pdo, $_GET);

// Edição de tipo de usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mudar_tipo'])) {
    $usuario_alvo = $_POST['usuario'] ?? 'desconhecido';
    atualizarTipoUsuario($pdo, $_POST);
    registrarOperacao('alterou tipo', $_SESSION['usuario'], $usuario_alvo);
    header("Location: painel_admin.php");
    exit;
}

// Exclusão de usuário
// if (isset($_GET['excluir'])) {
//     $id = (int)$_GET['excluir'];

//     // Buscar o nome do usuário antes de excluir
//     $usuario_alvo = buscarUsernamePorId($pdo, $id);

//     excluirUsuario($pdo, $id);
//     registrarOperacao('excluiu usuario', $_SESSION['usuario'], $usuario_alvo);

//     header("Location: painel_admin.php");
//     exit;
// }

// Exclusão de usuário
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];

    // Buscar o nome do usuário antes de excluir
    $usuario_alvo = buscarUsernamePorId($pdo, $id);

    excluirUsuario($pdo, $id); // Agora apenas exclui de tabelas centralizadas
    
    // registrarOperacao('EXCLUIR_USUARIO', $usuario_alvo, $_SESSION['usuario']['usuario'], $_SERVER['REMOTE_ADDR']);

    registrarOperacao('excluiu usuario', $_SESSION['usuario'], $usuario_alvo);

    header("Location: painel_admin.php");
    exit;
}

template_header('Painel Administrativo');
?>

<h2>Painel Administrativo</h2>
<p><a href="painel.php">Voltar</a> | <a href="logout.php">Sair</a></p>
<form method="GET">
    <label>Usuário: <input type="text" name="usuario" value="<?php echo htmlspecialchars($_GET['usuario'] ?? ''); ?>"></label>
    <label>Tipo:
        <select name="tipo">
            <option value="">Todos</option>
            <option value="admin" <?php if (($_GET['tipo'] ?? '') === 'admin') echo 'selected'; ?>>Admin</option>
            <option value="usuario" <?php if (($_GET['tipo'] ?? '') === 'usuario') echo 'selected'; ?>>Usuário</option>
        </select>
    </label>
    <label>Data de criação: <input type="date" name="criado_em" value="<?php echo htmlspecialchars($_GET['criado_em'] ?? ''); ?>"></label>
    <button type="submit">Filtrar</button>
</form><br>

<?php renderizarTabelaUsuarios($usuarios); ?>

<?php template_footer(); ?>
