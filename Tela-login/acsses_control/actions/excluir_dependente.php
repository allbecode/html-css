<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/auth.php';
require_once '../includes/db.php';
require_once '../includes/session.php';
require_once '../includes/log_functions.php';

verificaUsuarioLogado();

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario']['usuario'] ?? ($_SESSION['usuario'] ?? 'desconhecido');
$nomeDep = $_POST['nome'];
if (isset($_POST['dependente_id'])) {
    $stmt = $pdo->prepare("DELETE FROM dependentes WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute([
        ':id' => $_POST['dependente_id'],
        ':usuario_id' => $usuario_id
    ]);

    registrarOperacao(
        "Dependente $nomeDep Excluído",
        $usuario_nome,
        $usuario_nome
    );
    $_SESSION['mensagem_sucesso'] = 'Dpendente excluído com sucesso!!!';
}

header('Location: ../pages/perfil.php');
exit;
