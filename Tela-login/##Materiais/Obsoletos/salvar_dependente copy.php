<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session_start();
// var_dump($_SESSION);
// exit;

require_once '../includes/session.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/log_functions.php';

protegerPagina();

$usuario_id = $_SESSION['id'];

$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'] ?? null;
$relacionamento = $_POST['relacionamento'] ?? null;
$dependente_id = $_POST['dependente_id'] ?? null;

if ($dependente_id) {
    // Atualização
    $sql = "UPDATE dependentes SET nome = :nome, data_nascimento = :data_nascimento, relacionamento = :relacionamento WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':data_nascimento' => $data_nascimento,
        ':relacionamento' => $relacionamento,
        ':id' => $dependente_id,
        ':usuario_id' => $usuario_id
    ]);
} else {
    // Inserção
    $sql = "INSERT INTO dependentes (usuario_id, nome, data_nascimento, relacionamento) VALUES (:usuario_id, :nome, :data_nascimento, :relacionamento)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':nome' => $nome,
        ':data_nascimento' => $data_nascimento,
        ':relacionamento' => $relacionamento
    ]);
}

header('Location: ../pages/perfil.php');
exit;
