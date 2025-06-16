<?php
require_once '../includes/db_connection.php';
require_once '../includes/functions.php';
$pageClass = 'sem-menu';

$mes = $_GET['mes'] ?? date('m');
$ano = $_GET['ano'] ?? date('Y');

$sql = "SELECT nome, tipo, descricao, valor, data_vencimento 
        FROM transacoes 
        WHERE nome IN ('Dízimo', 'Oferta') AND mes = :mes AND ano = :ano ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
$stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
$stmt->execute();
$contribuicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = array_sum(array_column($contribuicoes, 'valor'));
?>