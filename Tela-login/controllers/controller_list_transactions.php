<?php 

require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';
require_once '../acsses_control/includes/functions.php';
session_start(); // Garante que a sessão está ativa

$anoAtual = date('Y');

$usuarioId = $_SESSION['usuario_id']; // Captura o ID do usuário logado

$condicoes = ["usuario_id = :usuario_id"]; // Filtro obrigatório
$params = [':usuario_id' => $usuarioId];

if (!empty($_GET['tipo'])) {
    $condicoes[] = "tipo = :tipo";
    $params[':tipo'] = $_GET['tipo'];
}

if (!empty($_GET['ano'])) {
    $condicoes[] = "YEAR(data_vencimento) = :ano";
    $params[':ano'] = $_GET['ano'];
}

if (!empty($_GET['mes'])) {
    $condicoes[] = "MONTH(data_vencimento) = :mes";
    $params[':mes'] = $_GET['mes'];
}

if (!empty($_GET['nome'])) {
    $condicoes[] = "nome LIKE :nome";
    $params[':nome'] = '%' . $_GET['nome'] . '%';
}

if (isset($_GET['pago']) && $_GET['pago'] !== '') {
    $condicoes[] = "pago = :pago";
    $params[':pago'] = $_GET['pago'];
}

$whereSQL = 'WHERE ' . implode(' AND ', $condicoes);

$sql = "SELECT * FROM transacoes $whereSQL ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>