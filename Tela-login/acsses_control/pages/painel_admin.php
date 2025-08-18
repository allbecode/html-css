<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/layout.php';
require_once '../includes/log_functions.php';
require_once '../includes/admin_functions.php';
require_once '../includes/header-login.php';

verificaUsuarioLogado();

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

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFind - Painel Admin</title>

    <link rel="stylesheet" href="../../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/segmentation/form-global.css">
    <link rel="stylesheet" href="../../assets/css/segmentation/layout-tables.css">

</head>

<body>

    <main>
        <h2>Painel Administrativo</h2>

        <form method="GET" class="form-geral">
            <label for="usuario">Usuário: </label>
            <input type="text" name="usuario" value="<?php echo htmlspecialchars($_GET['usuario'] ?? ''); ?>">
            <label>Tipo:
                <select name="tipo">
                    <option value="">Todos</option>
                    <option value="admin" <?php if (($_GET['tipo'] ?? '') === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="usuario" <?php if (($_GET['tipo'] ?? '') === 'usuario') echo 'selected'; ?>>Usuário</option>
                </select>
            </label>
            <label for="criado_em" >Data de criação: </label>
            <input type="date" name="criado_em" value="<?php echo htmlspecialchars($_GET['criado_em'] ?? ''); ?>">
            <button type="submit">Filtrar</button>
        </form><br>
        <div class="dashboard-card">
            <?php renderizarTabelaUsuarios($usuarios); ?>
        </div>
    </main>

</body>

</html>