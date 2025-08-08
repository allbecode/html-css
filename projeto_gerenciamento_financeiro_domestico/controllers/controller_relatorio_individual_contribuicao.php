<?php
// require_once '../includes/db_connection.php';
// require_once '../includes/functions.php';
// $pageClass = 'sem-menu';

// if (!isset($_GET['mes']) || !isset($_GET['ano']) || !isset($_GET['valor_dizimo'])) {
//     die("Dados insuficientes para gerar o relatório.");
// }

// $mes = $_GET['mes'];
// $ano = $_GET['ano'];
// $nome = $_GET['nome'];
// $valor = $_GET['valor_dizimo'];
// $descricao = $_GET['descricao'];




// require_once '../includes/db_connection.php';
require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';
require_once '../acsses_control/includes/functions.php';

$pageClass = 'sem-menu';

if (!verificaUsuarioLogado()) {
    header('Location: ../pages/login.php');
    exit;
}

$usuarioId = getUsuarioId();

// Verificação de parâmetros obrigatórios
$mes = $_GET['mes'] ?? null;
$ano = $_GET['ano'] ?? null;
$nome = $_GET['nome'] ?? null;
$valor = $_GET['valor_dizimo'] ?? null;
$descricao = $_GET['descricao'] ?? null;

if (!$mes || !$ano || !$valor || !$nome) {
    die("Dados insuficientes para gerar o relatório.");
}

// Carrega o nome do usuário e do dependente (se houver)
$dadosUsuario = buscarUsuarioEDependente($usuarioId);
$nomeUsuario = $dadosUsuario['usuario'];
$nomeDependente = $dadosUsuario['dependente']; // Pode ser null

?>