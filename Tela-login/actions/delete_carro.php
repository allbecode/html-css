<?php
require_once "../acsses_control/includes/auth.php";
require_once "../acsses_control/includes/db.php";
require_once '../acsses_control/includes/session.php';

if (isset($_GET['id'])) {
    $usuarioId = $_SESSION['usuario_id'];
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM carros WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute(['id' => $id, 'usuario_id' => $usuarioId]);
}

header("Location: ../pages/cadastro_carros.php");
exit;
