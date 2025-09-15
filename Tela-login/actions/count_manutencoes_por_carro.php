<?php
require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';

$usuarioId = $_SESSION['usuario_id'];

// Conta vencidas por carro
$stmt = $pdo->prepare("
    SELECT carro_id, COUNT(*) as vencidas
    FROM manutencoes_carro
    WHERE usuario_id = :id
      AND proxima_manut_data < CURDATE()
    GROUP BY carro_id
");
$stmt->execute(['id' => $usuarioId]);
$resultados = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [carro_id => vencidas]

echo json_encode($resultados);
