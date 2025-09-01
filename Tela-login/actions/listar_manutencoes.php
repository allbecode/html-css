<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../includes/manutencoes_functions.php';
require_once '../includes/functions.php';

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'] ?? 0;
$carroId = (int)($_GET['carro_id'] ?? 0);

$manutencoes = listarManutencoes($pdo, $usuarioId, $carroId);

// if (empty($manutencoes)) {
//     echo "<p>Nenhuma manutenção registrada ainda.</p>";
// } else {
//     echo "<ul>";
//     foreach ($manutencoes as $manutencao) {
//     echo "<li>" .  formatarDataBr($manutencao['data']) . " - 
//     {$manutencao['tipo']} - 
//     <button class='btn-editar button-icon' data-id='{$manutencao['id']}' title='Editar'>✏️</button>
//     <button class='btn-excluir button-icon' data-id='{$manutencao['id']}' title='Excluir'>🗑️</button></li>";
//     }
//     echo "</ul>";
// }


if (empty($manutencoes)) {
    echo "<p>Nenhuma manutenção registrada ainda.</p>";
} else {
    echo "<ul>";
    foreach ($manutencoes as $manutencao) {
    echo "<li> <button class='btn-editar button-modal' data-id='{$manutencao['id']}' title='Editar'>" .  formatarDataBr($manutencao['data']) . " - {$manutencao['tipo']} </button></li>";
}
    echo "</ul>";
}
