<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../includes/manutencoes_functions.php';

header('Content-Type: application/json; charset=utf-8');

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'] ?? 0;
$dados = $_POST;

// Verifica se o carro_id veio no POST
if (!isset($dados['carro_id']) || empty($dados['carro_id'])) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Carro não especificado.']);
    exit;
}

$carroId = (int)$dados['carro_id'];

// Chama função de cadastro
$res = cadastrarManutencao($pdo, $usuarioId, $carroId, $dados);

echo json_encode($res, JSON_UNESCAPED_UNICODE);
exit;
