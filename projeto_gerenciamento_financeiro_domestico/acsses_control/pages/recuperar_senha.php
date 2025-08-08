<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';
require_once '../includes/log_functions.php';
require_once '../includes/functions.php';

$msg = '';
$nova_senha_criada = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        if (!empty($_POST['nova_senha'])) {
            // Redefinir senha
            $nova_senha = hashSenha($_POST['nova_senha']);
            $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
            $update->execute([$nova_senha, $email]);
            $msg = "Senha atualizada com sucesso. <a href='login.php'>Faça login</a>";
            registrarOperacao('senha alterada', $usuario['username']);
            $nova_senha_criada = true;
        } else {
            // Solicitar nova senha
            $msg = "Usuário encontrado. Digite sua nova senha abaixo:";
        }
    } else {
        $msg = "E-mail não encontrado.";
    }
}
?>

<h2>Recuperar Senha</h2>
<form method="POST">
    E-mail: <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required><br><br>
    <?php if (isset($usuario) && $usuario && !$nova_senha_criada): ?>
        Nova senha: <input type="password" name="nova_senha" required><br><br>
    <?php endif; ?>
    <button type="submit"><?php echo isset($usuario) && !$nova_senha_criada ? 'Atualizar Senha' : 'Enviar'; ?></button>
</form>
<p><?php echo $msg; ?></p>
<p><a href="./login.php">Cancelar</a></p>
