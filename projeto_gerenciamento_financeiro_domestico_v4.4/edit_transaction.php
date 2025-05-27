<?php
header('Content-Type: application/json');
include 'db_connection.php';
include 'utils.php';


$data = json_decode(file_get_contents("php://input"), true);

$nome = $data['nome'];
$tipo = $data['tipo'];
$baseContribuicao = ($tipo === 'receita' && contribuicao_valida($nome)) ? 1 : 0;


if (!isset($data['id'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID ausente.']);
    exit;
}

$data_vencimento = $data['data_vencimento']; // yyyy-mm-dd
$mes = (int)date('n', strtotime($data_vencimento));
$ano = (int)date('Y', strtotime($data_vencimento));

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
WHERE id = :id";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ':nome' => $data['nome'],
        ':data_vencimento' => $data_vencimento,
        ':valor' => $data['valor'],
        ':tipo' => $data['tipo'],
        ':forma_pagamento' => $data['forma_pagamento'],
        ':descricao' => $data['descricao'],
        ':mes' => $mes,
        ':ano' => $ano,
        ':pago' => $data['pago'],
        ':base_contribuicao' => $baseContribuicao,
        ':id' => $data['id']
    ]);

    echo json_encode(['status' => 'ok']);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
