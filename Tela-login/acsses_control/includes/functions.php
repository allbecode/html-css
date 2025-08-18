<?php
require_once __DIR__. '/db.php';

define('BASE_URL', 'http://localhost/projetos/github/html-css/ProjetoGerenciamentoFinenceiro_teste/'); // ajuste conforme seu domínio/pasta raiz


function hashSenha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

function verificarSenha($senha, $hash) {
    return password_verify($senha, $hash);
}

function formataDataPtBr($data) {
    if (!$data) return '';
    $timestamp = strtotime($data);

    if (!$timestamp) return $data; // Se falhar, retorna a data original
    
    // Se contiver hora, retorna com hora
    if (strpos($data, ':') !== false) {
        return date('d/m/Y H:i', $timestamp);
    }
    // Apenas data
    return date('d/m/Y', $timestamp);
}

function buscarDependentesPorUsuarioId($usuario_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM dependentes WHERE usuario_id = :usuario_id");
    $stmt->execute([':usuario_id' => $usuario_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function usuarioExiste($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = :username");
    $stmt->execute([':username' => $username]);
    return $stmt->fetch() !== false;
}


?>
