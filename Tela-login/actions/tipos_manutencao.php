<?php
require_once '../acsses_control/includes/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query("SELECT id, nome FROM tipos_manutencao ORDER BY nome ASC");
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC); // array de objetos [{id, nome}, ...]

    echo json_encode($tipos, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao carregar tipos']);
}


