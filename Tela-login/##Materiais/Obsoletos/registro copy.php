<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/log_functions.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];

    try {
        $pdo = conectar();

        $stmt = $pdo->prepare("INSERT INTO usuarios (username, senha, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, hashSenha($senha), $email]);
        registrarOperacao('registrou usuário', $username);
        $msg = "Usuário registrado com sucesso! <a href='login.php'>Acessar</a>";
    } catch (PDOException $e) {
        $msg = "Erro: " . $e->getMessage();
    }
}
?>

<h2>Registrar Usuário</h2>
<form method="POST">
    Usuário: <input type="text" name="username" required><br><br>
    E-mail: <input type="email" name="email" required><br><br>
    Senha: <input type="password" name="senha" required><br><br>
    <button type="submit">Registrar</button>
</form>
<p><?php echo $msg; ?></p>
<p><a href="./login.php">Cancelar</a></p>
