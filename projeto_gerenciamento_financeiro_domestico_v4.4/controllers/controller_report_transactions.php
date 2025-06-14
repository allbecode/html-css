<?php
require_once '../includes/db_connection.php';
require_once '../includes/functions.php';

$receitas = [];
$despesas = [];
$totalReceitas = 0;
$totalDespesas = 0;
$saldoFinal = 0;
$mesAtual = date('m'); // De 1 a 12
$anoAtual = date('Y');
$mes = $_POST['mes'] ?? $mesAtual;
$ano = $_POST['ano'] ?? $anoAtual;

$sql = "SELECT * FROM transacoes WHERE mes = :mes AND ano = :ano ORDER BY data_vencimento ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':mes', $mes);
$stmt->bindParam(':ano', $ano);
$stmt->execute();
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($transacoes as $transacao) {
    if ($transacao['tipo'] === 'receita') {
        $receitas[] = $transacao;
        $totalReceitas += $transacao['valor'];
    } else {
        $despesas[] = $transacao;
        $totalDespesas += $transacao['valor'];
    }
}

$saldoFinal = $totalReceitas - $totalDespesas;
?>