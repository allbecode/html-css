<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../acsses_control/includes/db.php';
require_once __DIR__ . '/../acsses_control/includes/session.php';
include '../includes/utils.php';

verificaUsuarioLogado();

$usuario_id = $_SESSION['usuario_id'];

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID ausente.']);
    exit;
}

$nome = $data['nome'];
$tipo = $data['tipo'];
$data_vencimento = $data['data_vencimento'];
$valor = $data['valor'];
$forma_pagamento = $data['forma_pagamento'];
$descricao = $data['descricao'];
$pago = $data['pago'];

$mes = (int)date('n', strtotime($data_vencimento));
$ano = (int)date('Y', strtotime($data_vencimento));
$baseContribuicao = ($tipo === 'receita' && contribuicao_valida($nome)) ? 1 : 0;

$sql = "UPDATE transacoes SET 
    nome = :nome,
    data_vencimento = :data_vencimento,
    valor = :valor,
    tipo = :tipo,
    forma_pagamento = :forma_pagamento,
    descricao = :descricao,
    mes = :mes,
    ano = :ano,
    pago = :pago,
    base_contribuicao = :base_contribuicao
WHERE id = :id AND usuario_id = :usuario_id";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':nome' => $nome,
        ':data_vencimento' => $data_vencimento,
        ':valor' => $valor,
        ':tipo' => $tipo,
        ':forma_pagamento' => $forma_pagamento,
        ':descricao' => $descricao,
        ':mes' => $mes,
        ':ano' => $ano,
        ':pago' => $pago,
        ':base_contribuicao' => $baseContribuicao,
        ':id' => $data['id'],
        ':usuario_id' => $usuario_id
    ]);

    echo json_encode(['status' => 'ok']);

} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}

