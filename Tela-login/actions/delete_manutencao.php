<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../includes/manutencoes_functions.php';

verificaUsuarioLogado();

// Recebe o ID da manutenção
if (!isset($_POST['id'])) {
    echo "Erro: ID não informado.";
    exit;
}

$id = (int) $_POST['id'];
$usuarioId = $_SESSION['usuario_id'];

// Verifica se pertence ao usuário
$stmt = $pdo->prepare("SELECT m.id 
    FROM manutencoes_carro m
    INNER JOIN carros c ON c.id = m.carro_id
    WHERE m.id = :id AND c.usuario_id = :usuario_id
");
$stmt->execute(['id' => $id, 'usuario_id' => $usuarioId]);
if (!$stmt->fetch()) {
    echo "Erro: manutenção não encontrada ou sem permissão.";
    exit;
}

// Excluir
$stmt = $pdo->prepare("DELETE FROM manutencoes_carro WHERE id = :id");
if ($stmt->execute(['id' => $id])) {
    echo "success";
} else {
    echo "Erro ao excluir manutenção.";
}
