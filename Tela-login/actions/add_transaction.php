<?php
// // Adicionar transações
// require_once "../includes/db_connection.php";
// include '../includes/utils.php';

// $nome = $_POST['nome'];
// $data_vencimento = $_POST['data_vencimento'];
// $valor = $_POST['valor'];
// $tipo = $_POST['tipo'];
// $forma_pagamento = $_POST['forma_pagamento'];
// $descricao = $_POST['descricao'];
// $ano = (int)date('Y', strtotime($data_vencimento));
// $mes = (int)date('n', strtotime($data_vencimento));
// $dataRegstro = date('Y-m-d');
// $baseContribuicao = ($tipo === 'receita' && contribuicao_valida($nome)) ? 1 : 0;

// // Se for uma receita, marcar como "paga" automaticamente
// $pago = ($tipo === 'receita') ? 1 : 0;

// $sql = "INSERT INTO transacoes (
// nome, 
// data_vencimento, 
// valor, 
// tipo, 
// forma_pagamento, 
// descricao, 
// ano, 
// mes, 
// pago, 
// data_registro,
// base_contribuicao) 
// VALUES (
// :nome, 
// :data_vencimento, 
// :valor, 
// :tipo, 
// :forma_pagamento, 
// :descricao, 
// :ano, 
// :mes, 
// :pago, 
// :data_registro,
// :base_contribuicao)";

// $stmt = $pdo->prepare($sql);
// $stmt->bindParam(':nome', $nome);
// $stmt->bindParam(':data_vencimento', $data_vencimento);
// $stmt->bindParam(':valor', $valor);
// $stmt->bindParam(':tipo', $tipo);
// $stmt->bindParam(':forma_pagamento', $forma_pagamento);
// $stmt->bindParam(':descricao', $descricao);
// $stmt->bindParam(':ano', $ano);
// $stmt->bindParam(':mes', $mes);
// $stmt->bindParam(':pago', $pago);
// $stmt->bindParam(':data_registro', $dataRegstro);
// $stmt->bindParam(':base_contribuicao', $baseContribuicao, PDO::PARAM_BOOL);

// $stmt->execute();

// echo json_encode(['status' => 'ok']);
// exit();



// Adicionar transações
require_once "../acsses_control/includes/db.php";
require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/session.php'; // Garante acesso à sessão
require_once "../includes/utils.php";

// if (!isset($_SESSION['usuario_id'])) {
//     echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário não autenticado.']);
//     exit();
// }

verificaUsuarioLogado();

$usuario_id = $_SESSION['usuario_id'];

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
    usuario_id,
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
    base_contribuicao
) VALUES (
    :usuario_id,
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
    :base_contribuicao
)";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
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
