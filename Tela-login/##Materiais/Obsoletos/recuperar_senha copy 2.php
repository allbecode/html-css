<?php
require_once '../includes/session.php';
require_once '../includes/functions.php';
require_once '../includes/recovery_passwd_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = recuperarSenha($_POST['email']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="../../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/auth-forms.css">
</head>
<body>
<div class="login-container">
    <div class="login-logo">
            <h1>
                <i>GeFinD</i>
            </h1>
            <p>
                Gerenciamento Financeiro Doméstico
            </p>
        </div>
    <div>
        <h2>Recuperar Senha</h2>

        <?php if (!empty($mensagem)): ?>
            <div class="message <?= $mensagem['tipo']; ?>">
                <?= htmlspecialchars($mensagem['texto']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" id="email" placeholder="E-mail" required>

            <button type="submit" class="btn-submit">Enviar link de recuperação</button>
        </form>

        <div class="login-links">
            <a href="login.php">Voltar ao login</a>
        </div>
    </div>
</div>
</body>
</html>
