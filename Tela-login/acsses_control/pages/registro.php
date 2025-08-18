<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/log_functions.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);

    if (empty($username) || empty($email) || empty($nome) ||empty($senha) || empty($confirmar_senha)) {
        $msg = "Todos os campos são obrigatórios.";
    } elseif ($senha !== $confirmar_senha) {
        $msg = "As senhas não coincidem.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Email inválido.";
    } elseif (usuarioExiste($username)) {
        $msg = "Nome de usuário já está em uso.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, senha, nome, email, tipo) VALUES (:username, :senha, :nome, :email, 'usuario')");
        $stmt->execute([
            ':username' => $username,
            ':senha' => $senha_hash,
            ':nome' => $nome,
            ':email' => $email            
        ]);

        $usuario_id = $pdo->lastInsertId();
        registrarOperacao('REGISTRO', "Novo usuário: $username (ID: $usuario_id)", $username);

        header("Location: login.php?registrado=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFinD - New User</title>

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
        <h2>Registrar Novo Usuário</h2>
        <?php if (!empty($msg)): ?>
            <p class="login-message error"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Nome de Usuário" required><br><br>
            <input type="text" name="nome" placeholder="Primeiro Nome" required><br><br>
            <input type="email" name="email" placeholder="E-mail" required><br><br>
            <input type="password" name="senha" placeholder="Senha" required><br><br>
            <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required><br><br>
            <button type="submit">Registrar</button>
        </form>
        <div class="login-links">
            <p><a href="login.php">Já tem uma conta? Faça login</a></p>
        </div>
    </div>
</body>
</html>
