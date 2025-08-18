<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'session.php';
require_once 'layout.php';
require_once 'login_functions.php';
require_once 'log_functions.php';

session_start();
redirecionarSeLogado(); // Evita que usuário logado acesse a página de login novamente

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $login_resultado = validarLogin($usuario, $senha);

    if ($login_resultado['sucesso']) {
        $_SESSION['usuario'] = $usuario;
        registrarLogAcesso($usuario, 'login');
        header('Location: painel.php');
        exit;
    } else {
        $msg = $login_resultado['mensagem'];
    }
}

template_header('Login');
?>

<h2>Login</h2>
<?php if ($msg): ?>
    <p style="color:red;"><?php echo htmlspecialchars($msg); ?></p>
<?php endif; ?>

<form method="POST">
    <label>Usuário:</label><br>
    <input type="text" name="usuario" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">Entrar</button>
</form>

<p><a href="recuperar_senha.php">Esqueceu a senha?</a></p>

<?php template_footer(); ?>

