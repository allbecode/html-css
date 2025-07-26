<?php
require 'functions.php';
session_start();
// protegerPagina();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $senha = $_POST['senha'];

    $pdo = conectar();

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch();

    if ($usuario && verificarSenha($senha, $usuario['senha'])) {
    // Atualiza data do último login
    $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE username = ?");
    $stmt->execute([$username]);

    $_SESSION['usuario'] = $usuario['username'];
    $_SESSION['tipo'] = $usuario['tipo'];
    registrarLog($usuario['username'], 'login');
    header('Location: painel.php');
    exit;
    } else {
        $msg = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <body>
        <h2>Login</h2>
        <form method="POST">
            Usuário: <input type="text" name="username" required><br>
            Senha: <input type="password" name="senha" required><br>
            <button type="submit">Entrar</button>
        </form>
        <p><a href="recuperar_senha.php">Esqueci minha senha</a></p>
        <p><a href="registro.php">Cadastre-se</a></p>
        <p><?php echo $msg; ?></p>
    </body>
</html>

