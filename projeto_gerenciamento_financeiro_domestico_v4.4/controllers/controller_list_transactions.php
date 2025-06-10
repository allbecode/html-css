<?php 
require_once '../includes/db_connection.php';
require_once '../includes/functions.php';

$anoAtual = date('Y');

$sql = "SELECT * FROM transacoes ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$condicoes = [];
$params = [];

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

$whereSQL = count($condicoes) ? 'WHERE ' . implode(' AND ', $condicoes) : '';

$sql = "SELECT * FROM transacoes $whereSQL ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>