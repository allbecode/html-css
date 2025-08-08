<?php
// require_once '../includes/db_connection.php';
// require_once '../includes/functions.php';

// $dataAtual = date('Y-m-d');
// $diaAtual = date('d');
// $mesAtual = date('m');
// $anoAtual = date('Y');

// // Buscar despesas vencidas (não pagas)
// $sqlVencidas = "SELECT * FROM transacoes WHERE tipo = 'despesa' AND data_vencimento < :dataAtual AND mes = :mesAtual AND ano = :anoAtual AND pago = 0";
// $stmt = $pdo->prepare($sqlVencidas);
// $stmt->bindParam(':dataAtual', $dataAtual);
// $stmt->bindParam(':mesAtual', $mesAtual);
// $stmt->bindParam(':anoAtual', $anoAtual);
// $stmt->execute();
// $despesasVencidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // Buscar despesas com vencimento hoje (não pagas)
// $sqlHoje = "SELECT * FROM transacoes WHERE tipo = 'despesa' AND data_vencimento = :dataAtual AND mes = :mesAtual AND ano = :anoAtual AND pago = 0";
// $stmt = $pdo->prepare($sqlHoje);
// $stmt->bindParam(':dataAtual', $dataAtual);
// $stmt->bindParam(':mesAtual', $mesAtual);
// $stmt->bindParam(':anoAtual', $anoAtual);
// $stmt->execute();
// $despesasHoje = $stmt->fetchAll(PDO::FETCH_ASSOC);
// $totalVencidas = array_sum(array_column($despesasVencidas, 'valor'));

// // Calcular saldo do mês atual
// $sqlSaldo = "SELECT 
//                 SUM(CASE WHEN tipo = 'receita' THEN valor ELSE 0 END) AS totalReceitas,
//                 SUM(CASE WHEN tipo = 'despesa' THEN valor ELSE 0 END) AS totalDespesas
//             FROM transacoes 
//             WHERE mes = :mesAtual AND ano = :anoAtual";
// $stmt = $pdo->prepare($sqlSaldo);
// $stmt->bindParam(':mesAtual', $mesAtual);
// $stmt->bindParam(':anoAtual', $anoAtual);
// $stmt->execute();
// $saldo = $stmt->fetch(PDO::FETCH_ASSOC);
// $totalHoje = array_sum(array_column($despesasHoje, 'valor'));

// $saldoAtual = $saldo['totalReceitas'] - $saldo['totalDespesas'];





// require_once '../includes/db_connection.php';
require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';
require_once '../acsses_control/includes/functions.php';
// require_once '../includes/session.php';
require_once '../acsses_control/includes/session.php';

if (!isUsuarioLogado()) {
    header("Location: login.php");
    exit;
}

$usuarioId = $_SESSION['usuario_id'];

$dataAtual = date('Y-m-d');
$diaAtual = date('d');
$mesAtual = date('m');
$anoAtual = date('Y');

// Buscar despesas vencidas (não pagas)
$sqlVencidas = "SELECT * FROM transacoes 
    WHERE usuario_id = :usuario_id 
    AND tipo = 'despesa' 
    AND data_vencimento < :dataAtual 
    AND mes = :mesAtual 
    AND ano = :anoAtual 
    AND pago = 0";
$stmt = $pdo->prepare($sqlVencidas);
$stmt->bindParam(':usuario_id', $usuarioId);
$stmt->bindParam(':dataAtual', $dataAtual);
$stmt->bindParam(':mesAtual', $mesAtual);
$stmt->bindParam(':anoAtual', $anoAtual);
$stmt->execute();
$despesasVencidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar despesas com vencimento hoje (não pagas)
$sqlHoje = "SELECT * FROM transacoes 
    WHERE usuario_id = :usuario_id 
    AND tipo = 'despesa' 
    AND data_vencimento = :dataAtual 
    AND mes = :mesAtual 
    AND ano = :anoAtual 
    AND pago = 0";
$stmt = $pdo->prepare($sqlHoje);
$stmt->bindParam(':usuario_id', $usuarioId);
$stmt->bindParam(':dataAtual', $dataAtual);
$stmt->bindParam(':mesAtual', $mesAtual);
$stmt->bindParam(':anoAtual', $anoAtual);
$stmt->execute();
$despesasHoje = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalVencidas = array_sum(array_column($despesasVencidas, 'valor'));
$totalHoje = array_sum(array_column($despesasHoje, 'valor'));

// Calcular saldo do mês atual
$sqlSaldo = "SELECT 
    SUM(CASE WHEN tipo = 'receita' THEN valor ELSE 0 END) AS totalReceitas,
    SUM(CASE WHEN tipo = 'despesa' THEN valor ELSE 0 END) AS totalDespesas
    FROM transacoes 
    WHERE usuario_id = :usuario_id 
    AND mes = :mesAtual 
    AND ano = :anoAtual";
$stmt = $pdo->prepare($sqlSaldo);
$stmt->bindParam(':usuario_id', $usuarioId);
$stmt->bindParam(':mesAtual', $mesAtual);
$stmt->bindParam(':anoAtual', $anoAtual);
$stmt->execute();
$saldo = $stmt->fetch(PDO::FETCH_ASSOC);

$saldoAtual = $saldo['totalReceitas'] - $saldo['totalDespesas'];



?>