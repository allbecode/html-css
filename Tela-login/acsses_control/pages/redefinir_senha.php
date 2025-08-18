<?php

date_default_timezone_set('America/Sao_Paulo');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';
// require_once '../includes/functions.php';
// require_once '../includes/recovery_passwd_functions.php';
require_once '../includes/auth.php';

function validarToken($token)
{
    global $pdo;
    $sql = "SELECT id, token_expira FROM usuarios WHERE token_recuperacao = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        return false; // Token não encontrado
    }

    // Verifica se expirou
    if (strtotime($usuario['token_expira']) < time()) {
        return false; // Token expirado
    }

    return $usuario['id']; // Retorna ID do usuário
}

function redefinirSenha($usuarioId, $novaSenha)
{
    global $pdo;
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios 
            SET senha = ?, token_recuperacao = NULL, token_expira = NULL 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$hash, $usuarioId]);
}

$erro = "";
$sucesso = "";
$token = $_POST['token'] ?? $_GET['token'] ?? "";
$novaSenha = $_POST['nova_senha'] ?? "";
$confirmarSenha = $_POST['confirmar_senha'] ?? "";

// Etapa 1 → Usuário colou o token, vamos validar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etapa']) && $_POST['etapa'] === 'validar') {
    if ($usuarioId = validarToken($token)) {
        // Token válido → mostra formulário para redefinir senha
        $mostrarFormSenha = true;
    } else {
        $erro = "Token inválido ou expirado.";
    }
}

// Etapa 2 → Usuário já validou o token e agora está enviando nova senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['etapa']) && $_POST['etapa'] === 'redefinir') {
    if ($novaSenha === $confirmarSenha) {
        if ($usuarioId = validarToken($token)) {
            if (redefinirSenha($usuarioId, $novaSenha)) {
                $sucesso = "Senha redefinida com sucesso! Você já pode fazer login.";
            } else {
                $erro = "Erro ao redefinir a senha. Tente novamente.";
            }
        } else {
            $erro = "Token inválido ou expirado.";
        }
    } else {
        $erro = "As senhas não coincidem.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
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
        <h2>Redefinir Senha</h2>

        <?php if ($erro): ?>
            <p class="login-message error"><?= $erro ?></p>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <p class="login-message success"><?= $sucesso ?></p>
            <div class="login-links">
                <P><a href="login.php">Realizar Login</a></P>
            </div>';
        <?php elseif (!isset($mostrarFormSenha) && !$sucesso): ?>
            <!-- Formulário para colar token -->
            <form method="post">
                <input type="hidden" name="etapa" value="validar">
                <input type="text" name="token" placeholder="Insira seu token" required>
                <button type="submit">Validar Token</button>
            </form>
        <?php elseif (isset($mostrarFormSenha) && !$sucesso): ?>
            <!-- Formulário para redefinir senha -->
            <form method="post">
                <input type="hidden" name="etapa" value="redefinir">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="password" name="nova_senha" placeholder="Nova senha" required>
                <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required>
                <button type="submit">Redefinir Senha</button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>