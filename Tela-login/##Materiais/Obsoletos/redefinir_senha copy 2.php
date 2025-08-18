<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/recovery_passwd_functions.php';

// $mensagem = null;
// $token = $_GET['token'] ?? ($_POST['token'] ?? null);
// $usuario = null;

// // Se o token foi enviado (por GET ou POST)
// if ($token) {
//     // Busca o usuário pelo token
//     $stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacao = :token");
//     $stmt->execute([':token' => $token]);
//     $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

//     if (!$usuario) {
//         $mensagem = ['tipo' => 'error', 'texto' => 'Token inválido ou expirado.'];
//     } elseif (strtotime($usuario['token_expira']) < time()) {
//         $mensagem = ['tipo' => 'error', 'texto' => 'Token expirado. Solicite uma nova recuperação.'];
//     }

//     // Processa redefinição
//     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha']) && !$mensagem) {
//         $senha = trim($_POST['senha'] ?? '');
//         $confirmar = trim($_POST['confirmar_senha'] ?? '');

//         if (!$senha || !$confirmar) {
//             $mensagem = ['tipo' => 'error', 'texto' => 'Preencha todos os campos.'];
//         } elseif ($senha !== $confirmar) {
//             $mensagem = ['tipo' => 'error', 'texto' => 'As senhas não coincidem.'];
//         } else {
//             $hash = password_hash($senha, PASSWORD_DEFAULT);

//             $stmt = $pdo->prepare("UPDATE usuarios 
//                                    SET senha = :senha, token_recuperacao = NULL, token_expira = NULL 
//                                    WHERE id = :id");
//             $stmt->execute([
//                 ':senha' => $hash,
//                 ':id' => $usuario['id']
//             ]);

//             if ($stmt->rowCount()) {
//                 $mensagem = ['tipo' => 'success', 'texto' => 'Senha redefinida com sucesso! Você já pode fazer login.'];
//                 $token = null; // limpa o token para não reexibir formulário
//             } else {
//                 $mensagem = ['tipo' => 'error', 'texto' => 'Erro ao atualizar a senha.'];
//             }
//         }
//     }
// }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    if (empty($token)) {
        $erro = "Informe o token de redefinição.";
    } elseif ($senha !== $confirmar) {
        $erro = "As senhas não coincidem.";
    } else {
        // Busca no banco
        $stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacao = ?");
        $stmt->execute([$token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Verifica expiração
            if (strtotime($usuario['token_expira']) > time()) {
                // Atualiza a senha e limpa token
                $novaHash = password_hash($senha, PASSWORD_DEFAULT);
                $upd = $pdo->prepare("UPDATE usuarios 
                                      SET senha = ?, token_recuperacao = NULL, token_expira = NULL 
                                      WHERE id = ?");
                $upd->execute([$novaHash, $usuario['id']]);

                $sucesso = "Senha redefinida com sucesso!";
               
            } else {
                $erro = "Token expirado. Solicite uma nova recuperação.";
                
            }
        } else {
            $erro = "Token inválido.";
           
        }
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

    <?php if (!$token): ?>
        <!-- Formulário para inserir token -->
        <form method="POST">
            <input type="text" name="token" placeholder="Cole aqui o token recebido" required>
            <button type="submit" class="btn-submit">Validar Token</button>
        </form>
    <?php elseif ($usuario && (!$mensagem || $mensagem['tipo'] !== 'success')): ?>
        <!-- Formulário para redefinir senha -->
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
