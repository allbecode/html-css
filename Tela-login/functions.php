<?php
// Conexão com o banco (ajuste para seu ambiente)
function conectar() {
    return new PDO("mysql:host=localhost;dbname=master;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}

function hashSenha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

function verificarSenha($senha, $hash) {
    return password_verify($senha, $hash);
}

function protegerPagina() {
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }
}

function registrarLog($username, $acao) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';
    $pdo = conectar();
    $stmt = $pdo->prepare("INSERT INTO logs_acesso (username, ip, acao) VALUES (?, ?, ?)");
    $stmt->execute([$username, $ip, $acao]);
}

?>
