<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/layout.php';
require_once '../includes/login_functions.php';
require_once '../includes/log_functions.php';
require_once '../includes/functions.php';


redirecionarSeLogado(); // Evita que usuário logado acesse a página de login novamente

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $login_resultado = validarLogin($usuario, $senha);

    if ($login_resultado['sucesso']) {
        // Salvando dados essenciais a sessão
        $_SESSION['usuario'] = $login_resultado['dados']['username'];
        $_SESSION['tipo'] = $login_resultado['dados']['tipo'];
        $_SESSION['usuario_id'] = $login_resultado['dados']['id']; // ← ESSENCIAL para o dashboard funcionar
        // var_dump($_SESSION); // testando a sessão
        // exit;
        $_SESSION['nome'] = $login_resultado['dados']['nome'] ?? $login_resultado['dados']['username'];

        registrarOperacao('login', $_SESSION['usuario']);

        if (!empty($login_resultado['dados']['primeiro_acesso'])) {
            header('Location: perfil.php');
        } else {
            header('Location: ' . BASE_URL . 'pages/index.php');
        }
        exit;
    } else {
        $msg = $login_resultado['mensagem'];
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFinD - Login</title>
    <link rel="stylesheet" href="../../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>

    <div class="login-container">
        <!-- Logo / Nome -->
        <div class="login-logo">
            <h1>
                <i>GeFinD</i>
            </h1>
            <p>
                Gerenciamento Financeiro Doméstico
            </p>
        </div>

        <h2>Login</h2>
        <?php if (!empty($msg)): ?>
            <div class="login-message error">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuário"><br><br>
            <input type="password" name="senha" placeholder="Senha"><br><br>
            <button type="submit">Entrar</button>
        </form>
        <div class="login-links">
            <p><a href="recuperar_senha.php">Esqueceu a senha?</a></p>
            <p><a href="registro.php">Cadastrar-se</a></p>
        </div>
    </div>
</body>

</html>