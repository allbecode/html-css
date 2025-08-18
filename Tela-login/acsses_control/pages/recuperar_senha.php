<?php
require_once '../includes/db.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';
require_once '../includes/recovery_passwd_functions.php';

$mensagem = null;
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
    <script>
        function copiarToken() {
            const token = document.getElementById('token-text').innerText;
            navigator.clipboard.writeText(token).then(() => {
                alert('Token copiado!');
            });
        }
    </script>
</head>
<body>
<div class="login-container">
    <div class="login-logo">
        <h1><i>GeFinD</i></h1>
        <p>Gerenciamento Financeiro Doméstico</p>
    </div>
    <div>
        <h2>Recuperar Senha</h2>

        <?php if (!empty($mensagem) && $mensagem['tipo'] === 'error'): ?>
            <div class="message <?= $mensagem['tipo']; ?>">
                <?= htmlspecialchars($mensagem['texto']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($mensagem['tipo']) && $mensagem['tipo'] === 'success'): ?>
            <?php
                // extrai o token do texto retornado
                preg_match('/Token gerado:\s*([a-f0-9]+)/i', $mensagem['texto'], $match);
                $tokenGerado = $match[1] ?? '';
            ?>
            <div class="message <?= $mensagem['tipo']; ?>">
                <p><strong>Seu token:</strong> <span id="token-text"><?= htmlspecialchars($tokenGerado); ?></span></p>
            </div>
            <button type="button" onclick="copiarToken()">Copiar Token</button>
            <br>
            <button><a href="./redefinir_senha.php">Ir para Redefinição</a></button>
        
        <?php else: ?>
            <form method="POST" action="">
                <input type="email" name="email" id="email" placeholder="E-mail" required>
                <button type="submit" class="btn-submit">Enviar link de recuperação</button>
            </form>
        <?php endif; ?>

        <div class="login-links">
            <a href="login.php">Voltar ao login</a>
        </div>
    </div>
</div>
</body>
</html>



