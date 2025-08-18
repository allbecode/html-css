<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/session.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/log_functions.php';

verificaUsuarioLogado();

$usuario_id = $_SESSION['usuario_id'] ?? null;
$usuario_nome = $_SESSION['usuario']['usuario'] ?? ($_SESSION['usuario'] ?? 'desconhecido');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'IP desconhecido';

$nome = $_POST['nome'] ?? null;
$data_nascimento = $_POST['data_nascimento'] ?? null;
$relacionamento = $_POST['relacionamento'] ?? null;
$dependente_id = $_POST['dependente_id'] ?? null;

if (!$usuario_id || !$nome) {
    // Falha na sessão ou dados obrigatórios
    header('Location: ../pages/perfil.php?erro=dados_invalidos');
    exit;
}

if ($dependente_id) {
    // Atualização
    $sql = "UPDATE dependentes SET nome = :nome, data_nascimento = :data_nascimento, relacionamento = :relacionamento WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':data_nascimento' => $data_nascimento,
        ':relacionamento' => $relacionamento,
        ':id' => $dependente_id,
        ':usuario_id' => $usuario_id
    ]);

    // Log de atualização
    registrarOperacao(
        'ATUALIZAR_DEPENDENTE',
        $nome,
        $usuario_nome,
        $ip
    );

    $_SESSION['mensagem_sucesso'] = "Dependente atualizado com sucesso!";
} else {
    // Inserção
    $sql = "INSERT INTO dependentes (usuario_id, nome, data_nascimento, relacionamento) VALUES (:usuario_id, :nome, :data_nascimento, :relacionamento)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':nome' => $nome,
        ':data_nascimento' => $data_nascimento,
        ':relacionamento' => $relacionamento
    ]);

    // Log de inserção
    registrarOperacao(
        "Dependente $nome Adiconado",
        $usuario_nome,
        $usuario_nome,
        $ip
    );
    $_SESSION['mensagem_sucesso'] = "Dependente adicionado com sucesso!";
}

header('Location: ../pages/perfil.php');
exit;
