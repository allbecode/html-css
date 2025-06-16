<?php

require_once '../includes/db_connection.php';
require_once '../includes/functions.php';

$mesSelecionado = $_POST['mes'] ?? date('m');
$anoSelecionado = $_POST['ano'] ?? date('Y');
$tipoSelecionado = $_POST['tipo_contribuicao'] ?? 'dizimo';

$totalReceitas = 0;
$valorEsperadoDizimo = 0;
$valorEsperadoOferta = 0;
$valorDizimado = 0;
$valorOfertado = 0;
$valorRestanteDizimo = 0;
$valorRestanteOferta = 0;
$temReceitas = true;

$mes = date('m');
$ano = date('Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];

    // Total de receitas
    $stmt = $pdo->prepare("SELECT SUM(valor) FROM transacoes 
    WHERE tipo = 'receita' 
    AND base_contribuicao = 1 /* valida o flag base_contribuicao */
    AND mes = :mes AND ano = :ano");
    $stmt->execute(['mes' => $mes, 'ano' => $ano]);
    $totalReceitas = $stmt->fetchColumn() ?: 0;

    // Verifica se há receitas
    $temReceitas = temReceitas($totalReceitas);

    // Dízimo
    $valorEsperadoDizimo = calcularDizimoOferta($totalReceitas);
    $stmt = $pdo->prepare("SELECT SUM(valor) FROM transacoes WHERE tipo = 'despesa' AND nome = 'Dízimo' AND mes = :mes AND ano = :ano");
    $stmt->execute(['mes' => $mes, 'ano' => $ano]);
    $valorDizimado = $stmt->fetchColumn() ?: 0;
    $valorRestanteDizimo = max($valorEsperadoDizimo - $valorDizimado, 0);

    // Oferta
    $valorEsperadoOferta = calcularDizimoOferta($totalReceitas);
    $stmt = $pdo->prepare("SELECT SUM(valor) FROM transacoes WHERE tipo = 'despesa' AND nome = 'Oferta' AND mes = :mes AND ano = :ano");
    $stmt->execute(['mes' => $mes, 'ano' => $ano]);
    $valorOfertado = $stmt->fetchColumn() ?: 0;
    $valorRestanteOferta = max($valorEsperadoOferta - $valorOfertado, 0);
}
?>