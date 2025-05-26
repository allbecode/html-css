<?php
header('Content-Type: application/json');
include 'db_connection.php';


$data = json_decode(file_get_contents("php://input"), true);


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
    pago = :pago
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
        ':id' => $data['id']
    ]);

    echo json_encode(['status' => 'ok']);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
}
