<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/recovery_passwd_functions.php';

$mensagem = null;
$usuario = null;
$token = isset($_POST['token']) ? trim($_POST['token']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se veio token + senha → etapa de redefinição
    if (!empty($_POST['senha']) && !empty($_POST['confirmar_senha'])) {
        $senha = $_POST['senha'];
        $confirmar = $_POST['confirmar_senha'];

        if ($senha !== $confirmar) {
            $mensagem = ['tipo' => 'error', 'texto' => 'As senhas não coincidem.'];
        } else {
            // Confere token no banco
            $stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacao = ?");
            $stmt->execute([$token]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                if (strtotime($usuario['token_expira']) > time()) {
                    $novaHash = password_hash($senha, PASSWORD_DEFAULT);
                    $upd = $pdo->prepare("UPDATE usuarios 
                        SET senha = ?, token_recuperacao = NULL, token_expira = NULL 
                        WHERE id = ?");
                    $upd->execute([$novaHash, $usuario['id']]);
                    $mensagem = ['tipo' => 'success', 'texto' => 'Senha redefinida com sucesso!'];
                } else {
                    $mensagem = ['tipo' => 'error', 'texto' => 'Token expirado. Solicite uma nova recuperação.'];
                }
            } else {
                $mensagem = ['tipo' => 'error', 'texto' => 'Token inválido.'];
            }
        }

    // Se só veio token → etapa de validação
    } elseif (!empty($token)) {
        $stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacao = ?");
        $stmt->execute([$token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && strtotime($usuario['token_expira']) > time()) {
            // token válido — mostra campos de senha
            $mensagem = ['tipo' => 'info', 'texto' => 'Token válido. Digite a nova senha.'];
        } else {
            $mensagem = ['tipo' => 'error', 'texto' => 'Token inválido ou expirado.'];
            $token = ''; // limpa para reexibir campo token
        }
    } else {
        $mensagem = ['tipo' => 'error', 'texto' => 'Informe o token.'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="../../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/auth-forms.css">
</head>
<body>
<div class="login-container">
    <div class="login-logo">
        <h1><i>GeFinD</i></h1>
        <p>Gerenciamento Financeiro Doméstico</p>
    </div>
    <h1>Redefinir Senha</h1>

    <?php if ($mensagem): ?>
        <div class="message <?= $mensagem['tipo'] ?>">
            <?= htmlspecialchars($mensagem['texto']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($token)): ?>
        <!-- Etapa 1: inserir token -->
        <form method="POST">
            <input type="text" name="token" placeholder="Cole aqui o token recebido" required>
            <button type="submit" class="btn-submit">Validar Token</button>
        </form>
    <?php elseif ($usuario && $mensagem && $mensagem['tipo'] === 'info'): ?>
        <!-- Etapa 2: redefinir senha -->
        <form method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="password" name="senha" placeholder="Nova Senha" required>
            <input type="password" name="confirmar_senha" placeholder="Confirmar Nova Senha" required>
            <button type="submit" class="btn-submit">Redefinir Senha</button>
        </form>
    <?php endif; ?>

    <div class="login-links">
        <a href="login.php">Voltar ao login</a>
    </div>
</div>
</body>
</html>



