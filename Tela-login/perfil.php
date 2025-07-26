<?php
require 'functions.php';
session_start();
protegerPagina(); // Só acessível por usuário logado

$pdo = conectar();
$username = $_SESSION['usuario'];
$msg = "";

// Buscar dados do usuário logado
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$username]);
$usuario = $stmt->fetch();

// Alterar senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];

    if (verificarSenha($senha_atual, $usuario['senha'])) {
        $hash = hashSenha($nova_senha);
        $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE username = ?");
        $update->execute([$hash, $username]);
        $msg = "Senha atualizada com sucesso!";
    } else {
        $msg = "Senha atual incorreta!";
    }
}
?>

<h2>Meu Perfil</h2>
<p><a href="painel.php">Voltar</a> | <a href="logout.php">Sair</a></p>

<p><strong>Usuário:</strong> <?php echo htmlspecialchars($usuario['username']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
<p><strong>Tipo:</strong> <?php echo $usuario['tipo']; ?></p>
<p><strong>Último login:</strong> <?php echo $usuario['ultimo_login'] ?? 'Nunca'; ?></p>

<hr>
<h3>Alterar Senha</h3>
<form method="POST">
    Senha atual: <input type="password" name="senha_atual" required><br>
    Nova senha: <input type="password" name="nova_senha" required><br>
    <button type="submit">Atualizar Senha</button>
</form>

<p><?php echo $msg; ?></p>
