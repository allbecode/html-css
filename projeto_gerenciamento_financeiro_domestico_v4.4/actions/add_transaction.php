<?php
// Adicionar transações
require_once "../includes/db_connection.php";
include '../includes/utils.php';

$nome = $_POST['nome'];
$data_vencimento = $_POST['data_vencimento'];
$valor = $_POST['valor'];
$tipo = $_POST['tipo'];
$forma_pagamento = $_POST['forma_pagamento'];
$descricao = $_POST['descricao'];
$ano = (int)date('Y', strtotime($data_vencimento));
$mes = (int)date('n', strtotime($data_vencimento));
$dataRegstro = date('Y-m-d');
$baseContribuicao = ($tipo === 'receita' && contribuicao_valida($nome)) ? 1 : 0;

// Se for uma receita, marcar como "paga" automaticamente
$pago = ($tipo === 'receita') ? 1 : 0;

$sql = "INSERT INTO transacoes (
nome, 
data_vencimento, 
valor, 
tipo, 
forma_pagamento, 
descricao, 
ano, 
mes, 
pago, 
data_registro,
base_contribuicao) 
VALUES (
:nome, 
:data_vencimento, 
:valor, 
:tipo, 
:forma_pagamento, 
:descricao, 
:ano, 
:mes, 
:pago, 
:data_registro,
:base_contribuicao)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':data_vencimento', $data_vencimento);
$stmt->bindParam(':valor', $valor);
$stmt->bindParam(':tipo', $tipo);
$stmt->bindParam(':forma_pagamento', $forma_pagamento);
$stmt->bindParam(':descricao', $descricao);
$stmt->bindParam(':ano', $ano);
$stmt->bindParam(':mes', $mes);
$stmt->bindParam(':pago', $pago);
$stmt->bindParam(':data_registro', $dataRegstro);
$stmt->bindParam(':base_contribuicao', $baseContribuicao, PDO::PARAM_BOOL);

$stmt->execute();

echo json_encode(['status' => 'ok']);
exit();
