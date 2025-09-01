<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/functions.php';
require_once '../acsses_control/includes/session.php';
require_once '../includes/functions.php';

$mesSelecionado = $_POST['mes'] ?? date('m');
$anoSelecionado = $_POST['ano'] ?? date('Y');
$tipoSelecionado = $_POST['tipo_contribuicao'] ?? 'dízimo';

$totalReceitas = 0;
$valorEsperadoDizimo = 0;
$valorEsperadoOferta = 0;
$valorDizimado = 0;
$valorOfertado = 0;
$valorRestanteDizimo = 0;
$valorRestanteOferta = 0;
$temReceitas = true;

$mes = $mesSelecionado;
$ano = $anoSelecionado;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Função auxiliar para buscar somatório de valores
    $buscarSoma = function($tipo, $nome = null) use ($pdo, $usuarioId, $mes, $ano) {
        $query = "
            SELECT SUM(valor) FROM transacoes 
            WHERE usuario_id = :usuario_id
            AND tipo = :tipo
            AND mes = :mes AND ano = :ano
        ";
        $params = [
            'usuario_id' => $usuarioId,
            'tipo' => $tipo,
            'mes' => $mes,
            'ano' => $ano
        ];

        if ($nome !== null) {
            $query .= " AND nome = :nome";
            $params['nome'] = $nome;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn() ?: 0;
    };

    // Total de receitas válidas para contribuição
    $stmt = $pdo->prepare("
        SELECT SUM(valor) FROM transacoes 
        WHERE usuario_id = :usuario_id
        AND tipo = 'receita' 
        AND base_contribuicao = 1
        AND mes = :mes AND ano = :ano
    ");
    $stmt->execute([
        'usuario_id' => $usuarioId,
        'mes' => $mes,
        'ano' => $ano
    ]);
    $totalReceitas = $stmt->fetchColumn() ?: 0;

    $temReceitas = temReceitas($totalReceitas);

    // Cálculos de Dízimo e Oferta
    $valorEsperadoDizimo = calcularDizimoOferta($totalReceitas);
    $valorDizimado = $buscarSoma('despesa', 'Dízimo');
    $valorRestanteDizimo = max($valorEsperadoDizimo - $valorDizimado, 0);

    $valorEsperadoOferta = calcularDizimoOferta($totalReceitas);
    $valorOfertado = $buscarSoma('despesa', 'Oferta');
    $valorRestanteOferta = max($valorEsperadoOferta - $valorOfertado, 0);
}

?>
