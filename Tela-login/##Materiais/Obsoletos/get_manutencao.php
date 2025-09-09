<?php


// $id = $_GET['id'] ?? 0;
// $stmt = $pdo->prepare("SELECT * FROM manutencoes_carro WHERE id = ?");
// $stmt->execute([$id]);

// echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));

require_once '../acsses_control/includes/db.php';

if (!isset($_GET['id'])) {
    echo json_encode(['erro' => 'ID não informado']);
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("
    SELECT 
        id, 
        carro_id, 
        tipo, 
        descricao, 
        data, 
        km, 
        valor, 
        pago, 
        proxima_manut_data, 
        proxima_manut_km
    FROM manutencoes_carro 
    WHERE id = :id
");
$stmt->execute(['id' => $id]);

$manutencao = $stmt->fetch(PDO::FETCH_ASSOC);

if ($manutencao) {
    echo json_encode($manutencao);
} else {
    echo json_encode(['erro' => 'Manutenção não encontrada']);
}

