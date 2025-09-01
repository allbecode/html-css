<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../includes/manutencoes_functions.php';

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'] ?? 0;
$dados = $_POST;
$carroId = (int)$dados['carro_id'];

$res = cadastrarManutencao($pdo, $usuarioId, $carroId, $dados);

header('Content-Type: application/json');
echo json_encode($res);
