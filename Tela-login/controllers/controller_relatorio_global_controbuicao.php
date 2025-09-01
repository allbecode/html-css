<?php
require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';
require_once '../acsses_control/includes/functions.php';

$pageClass = 'sem-menu';

// Garante que o usuário esteja logado
verificaUsuarioLogado();

// Obtém ID do usuário logado
$usuarioId = $_SESSION['usuario_id'] ?? null;

// Mês e ano (padrão: mês/ano atual)
$mes = $_GET['mes'] ?? date('m');
$ano = $_GET['ano'] ?? date('Y');

// Buscar nome do usuário logado
// $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = :id");
// $stmt->execute(['id' => $usuarioId]);
// $usuarioNome = $stmt->fetchColumn() ?: 'Usuário';

// Buscar nome e sobrenome do usuário logado
$stmt = $pdo->prepare("SELECT nome, sobrenome FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $usuarioId]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    // Se existir sobrenome, concatena, caso contrário exibe só o nome
    $usuarioNome = $usuario['sobrenome'] 
        ? $usuario['nome'] . ' ' . $usuario['sobrenome'] 
        : $usuario['nome'];
} else {
    $usuarioNome = 'Usuário';
}

// Buscar todos os dependentes do usuário logado
$stmt = $pdo->prepare("SELECT nome FROM dependentes WHERE usuario_id = :usuario_id ORDER BY id ASC");
$stmt->execute(['usuario_id' => $usuarioId]);
$dependentes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Monta array com os nomes das colunas (usuário + dependentes)
$colunas = array_merge([$usuarioNome], $dependentes);


// Buscar contribuições (agrupadas por nome)
$sql = "SELECT nome, tipo, descricao, valor, data_vencimento 
        FROM transacoes 
        WHERE nome IN ('Dízimo', 'Oferta') 
          AND usuario_id = :usuario_id
          AND MONTH(data_vencimento) = :mes
          AND YEAR(data_vencimento) = :ano
        ORDER BY nome ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'usuario_id' => $usuarioId,
    'mes' => $mes,
    'ano' => $ano
]);
$contribuicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total geral
$total = array_sum(array_column($contribuicoes, 'valor'));
