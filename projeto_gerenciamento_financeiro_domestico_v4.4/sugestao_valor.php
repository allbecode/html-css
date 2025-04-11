<?php
include 'db_connection.php';

$mes = $_GET['mes'];
$ano = $_GET['ano'];

// Calcular o total de receitas do mês selecionado
$sqlReceitas = "SELECT SUM(valor) as totalReceitas FROM transacoes WHERE tipo = 'receita' AND mes = :mes AND ano = :ano";
$stmtReceitas = $pdo->prepare($sqlReceitas);
$stmtReceitas->bindParam(':mes', $mes);
$stmtReceitas->bindParam(':ano', $ano);
$stmtReceitas->execute();
$resultadoReceitas = $stmtReceitas->fetch(PDO::FETCH_ASSOC);
$totalReceitas = $resultadoReceitas['totalReceitas'] ?? 0;

// Calcular o total de ofertas já cadastradas no mês selecionado
$sqlOfertas = "SELECT SUM(valor) as totalOfertas FROM transacoes WHERE nome = 'Oferta' AND mes = :mes AND ano = :ano";
$stmtOfertas = $pdo->prepare($sqlOfertas);
$stmtOfertas->bindParam(':mes', $mes);
$stmtOfertas->bindParam(':ano', $ano);
$stmtOfertas->execute();
$resultadoOfertas = $stmtOfertas->fetch(PDO::FETCH_ASSOC);
$totalOfertasCadastradas = $resultadoOfertas['totalOfertas'] ?? 0;

// Calcular o quanto ainda pode ser ofertado
$limiteOferta = $totalReceitas * 0.10;
$sugestao = max(0, $limiteOferta - $totalOfertasCadastradas);

// Retornar a sugestão como JSON
echo json_encode(["sugestao" => number_format($sugestao, 2, '.', '')]);
?>
