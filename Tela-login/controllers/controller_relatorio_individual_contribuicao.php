<?php
require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/usuario.php';
require_once '../acsses_control/includes/functions.php';
require_once '../includes/functions.php';

$pageClass = 'sem-menu';

// Garante que o usuário esteja logado
verificaUsuarioLogado();

// Obtém ID do usuário logado
$usuarioId = $_SESSION['usuario_id'] ?? null;

// Coleta e sanitiza parâmetros obrigatórios
$mes       = isset($_GET['mes']) ? (int) $_GET['mes'] : null;
$ano       = isset($_GET['ano']) ? (int) $_GET['ano'] : null;
$nome      = isset($_GET['nome']) ? trim($_GET['nome']) : null;
$valor     = isset($_GET['valor_dizimo']) ? (float) $_GET['valor_dizimo'] : null;
$descricao = isset($_GET['descricao']) ? trim($_GET['descricao']) : null;

// Validação dos parâmetros obrigatórios
if (!$mes || !$ano || !$valor || !$nome) {
    // Log opcional para rastrear tentativa de acesso inválida
    registrarOperacao('RELATORIO_INDIVIDUAL_FALHA', 'Faltam parâmetros', $usuarioId, $_SERVER['REMOTE_ADDR']);

    // Mensagem amigável
    $_SESSION['mensagem'] = [
        'tipo' => 'erro',
        'texto' => 'Não foi possível gerar o relatório. Verifique se todos os dados foram informados.'
    ];
    header("Location: ../pages/relatorios.php");
    exit;
}

// Carrega o nome do usuário e do dependente (se houver)
$dadosUsuario  = buscarUsuarioEDependente($usuarioId);
$nomeUsuario   = $dadosUsuario['usuario'] ?? 'Usuario';
$dependentes = $dadosUsuario['dependentes'] ?? []; // Pode ser null

