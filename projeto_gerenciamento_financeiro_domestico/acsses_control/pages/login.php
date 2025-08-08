<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/session.php';
require_once '../includes/layout.php';
require_once '../includes/login_functions.php';
require_once '../includes/log_functions.php';
require_once '../includes/functions.php';

session_start();
redirecionarSeLogado(); // Evita que usuário logado acesse a página de login novamente

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $login_resultado = validarLogin($usuario, $senha);

    if ($login_resultado['sucesso']) {
        $_SESSION['usuario'] = $login_resultado['dados']['username'];
        $_SESSION['tipo'] = $login_resultado['dados']['tipo'];
        $_SESSION['id'] = $login_resultado['dados']['id']; // ← ESSENCIAL para o dashboard funcionar
        $_SESSION['nome'] = $login_resultado['dados']['nome'] ?? $login_resultado['dados']['username'];
        $_SESSION['usuario_banco'] = $login_resultado['dados']['finaceiro_' . $login_resultado['dados']['id'] . '_' . strtolower(preg_replace('/[^a-z0-9_]/i', '_', $login_resultado['dados']['username']))];

        registrarOperacao('login', $_SESSION['usuario']);
        if (!empty($login_resultado['dados']['primeiro_acesso'])) {
            header('Location: perfil.php');
        } else {
            header('Location: painel.php');
        }
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
<p><a href="registro.php">Cadastrar-se</a></p>

<?php template_footer(); ?>