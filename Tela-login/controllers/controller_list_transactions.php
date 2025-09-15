<?php 
require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/functions.php';
require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';

// Garante que o usuário está logado
verificaUsuarioLogado();

$anoAtual = date('Y');

$usuarioId = $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? null; // Captura o ID do usuário logado

$condicoes = ["t.usuario_id = :usuario_id"]; // Filtro obrigatório
$params = [':usuario_id' => $usuarioId];

if (!empty($_GET['tipo'])) {
    $condicoes[] = "t.tipo = :tipo";
    $params[':tipo'] = $_GET['tipo'];
}

if (!empty($_GET['ano'])) {
    $condicoes[] = "YEAR(data_vencimento) = :ano";
    $params[':ano'] = $_GET['ano'];
}

if (!empty($_GET['mes'])) {
    $condicoes[] = "MONTH(data_vencimento) = :mes";
    $params[':mes'] = $_GET['mes'];
}

if (!empty($_GET['nome'])) {
    $condicoes[] = "t.nome LIKE :nome";
    $params[':nome'] = '%' . $_GET['nome'] . '%';
}

if (isset($_GET['pago']) && $_GET['pago'] !== '') {
    $condicoes[] = "t.pago = :pago";
    $params[':pago'] = $_GET['pago'];
}

$whereSQL = 'WHERE ' . implode(' AND ', $condicoes);

// 🔹 Query ajustada com LEFT JOIN para buscar vínculo com manutenção
$sql = "SELECT 
            t.*, 
            m.id AS manutencao_id
        FROM transacoes t
        LEFT JOIN manutencoes_carro m 
            ON m.transacao_id = t.id
        $whereSQL 
        ORDER BY t.data_vencimento ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>