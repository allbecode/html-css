<?php
require 'functions.php';

$pdo = conectar();

// Verifica se já existe algum usuário
$verifica = $pdo->query("SELECT COUNT(*) FROM usuarios");
$quantidade = $verifica->fetchColumn();

if ($quantidade > 0) {
    echo "A tabela 'usuarios' já possui usuários. Nenhuma ação foi realizada.";
    exit;
}

// Dados do admin padrão
$username = 'admin';
$senha = hashSenha('admin123');
$email = 'admin@admin.com';
$tipo = 'admin';

// Inserção do admin
$stmt = $pdo->prepare("INSERT INTO usuarios (username, senha, email, tipo) VALUES (?, ?, ?, ?)");
$stmt->execute([$username, $senha, $email, $tipo]);

echo "Administrador inicial criado com sucesso!<br>";
echo "Login: <strong>admin</strong><br>";
echo "Senha: <strong>admin123</strong><br>";
echo "<br>Por segurança, delete este arquivo após a inicialização.";

