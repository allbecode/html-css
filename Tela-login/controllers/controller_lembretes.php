<?php
require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;
if (!$usuarioId) {
    header("Location: ../acsses_control/pages/login.php");
    exit;
}

$sql = "
    SELECT m.id, m.carro_id, c.apelido AS carro_nome, t.nome AS tipo_nome, 
       m.proxima_manut_data, m.proxima_manut_km
    FROM manutencoes_carro m
    JOIN carros c ON m.carro_id = c.id
    JOIN tipos_manutencao t ON m.tipo_id = t.id
    WHERE m.usuario_id = :usuario_id
    AND (
        (m.proxima_manut_data < CURDATE())  -- vencida
        OR (m.proxima_manut_data BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)) -- a vencer
    )
    ORDER BY m.proxima_manut_data ASC;
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario' => $usuarioId]);
$lembretes = $stmt->fetchAll(PDO::FETCH_ASSOC);
