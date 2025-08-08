<?php
// require_once '../includes/db_connection.php';
// require_once '../includes/functions.php';
// $pageClass = 'sem-menu';

// $mes = $_GET['mes'] ?? date('m');
// $ano = $_GET['ano'] ?? date('Y');

// $sql = "SELECT nome, tipo, descricao, valor, data_vencimento 
//         FROM transacoes 
//         WHERE nome IN ('Dízimo', 'Oferta') AND mes = :mes AND ano = :ano ORDER BY nome ASC";

// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
// $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
// $stmt->execute();
// $contribuicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $total = array_sum(array_column($contribuicoes, 'valor'));





// require_once '../includes/db_connection.php';
// require_once '../includes/functions.php';
// require_once '../includes/session.php';

// $pageClass = 'sem-menu';

// if (!isUsuarioLogado()) {
//     http_response_code(403);
//     exit('Acesso negado: usuário não autenticado.');
// }

// $usuarioId = $_SESSION['usuario_id'] ?? null;

// $mes = $_GET['mes'] ?? date('m');
// $ano = $_GET['ano'] ?? date('Y');

// $sql = "SELECT nome, tipo, descricao, valor, data_vencimento 
//         FROM transacoes 
//         WHERE usuario_id = :usuario_id
//         AND nome IN ('Dízimo', 'Oferta') 
//         AND mes = :mes AND ano = :ano 
//         ORDER BY nome ASC";

// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(':usuario_id', $usuarioId, PDO::PARAM_INT);
// $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
// $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);
// $stmt->execute();
// $contribuicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $total = array_sum(array_column($contribuicoes, 'valor'));




// require_once '../includes/db_connection.php';
require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';
require_once '../acsses_control/includes/functions.php';
$pageClass = 'sem-menu';

verificaUsuarioLogado(); // garante que o usuário está logado
$usuarioId = $_SESSION['usuario']['id'];

$mes = $_GET['mes'] ?? date('m');
$ano = $_GET['ano'] ?? date('Y');

// Buscar nome do usuário logado
$stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $usuarioId]);
$usuarioNome = $stmt->fetchColumn() ?? 'Usuário';

// Buscar primeiro dependente (se houver)
$stmt = $pdo->prepare("SELECT nome FROM dependentes WHERE usuario_id = :usuario_id ORDER BY id ASC LIMIT 1");
$stmt->execute(['usuario_id' => $usuarioId]);
$dependenteNome = $stmt->fetchColumn(); // null se não houver

// Monta array com os nomes das colunas
$colunas = [$usuarioNome];
if ($dependenteNome) {
    $colunas[] = $dependenteNome;
}

// Buscar contribuições (agrupadas por nome)
$sql = "SELECT nome, tipo, descricao, valor, data_vencimento 
        FROM transacoes 
        WHERE nome IN ('Dízimo', 'Oferta') 
        AND usuario_id = :usuario_id
        AND mes = :mes AND ano = :ano 
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
