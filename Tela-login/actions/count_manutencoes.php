<?php
require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';

$usuarioId = $_SESSION['usuario_id'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM manutencoes_carro 
    WHERE usuario_id = :id
    AND proxima_manut_data <= CURDATE()
");
$stmt->execute(['id' => $usuarioId]);
$vencidas = $stmt->fetchColumn();

echo json_encode(['vencidas' => (int)$vencidas]);

