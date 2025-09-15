<?php
require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';

$usuarioId = $_SESSION['usuario_id'] ?? null;

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $revisado = isset($_POST["revisado"]) ? 1 : 0;

    if ($id) {
        $stmt = $pdo->prepare("UPDATE manutencoes_carro SET revisado = :revisado WHERE id = :id");
        $ok = $stmt->execute([
            ":revisado" => $revisado,
            ":id" => $id
        ]);

        if ($ok) {
            echo json_encode(["success" => true]);
            exit;
        }
    }
    echo json_encode(["success" => false, "message" => "Falha ao atualizar"]);
}
