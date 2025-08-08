<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/layout.php';
require_once '../includes/dashboard_functions.php';
require_once '../includes/functions.php';
require_once '../includes/log_functions.php';

protegerPagina();

if ($_SESSION['tipo'] !== 'admin') {
    header('Location: painel.php');
    exit;
}

$pdo = conectar();
$estatisticas = getEstatisticasGerais($pdo);
$ultimos_usuarios = getUltimosUsuarios($pdo);

$filtros = [
    'data' => $_GET['data'] ?? null,
    'usuario' => $_GET['usuario'] ?? null,
    'ip' => $_GET['ip'] ?? null,
    'acao' => $_GET['acao'] ?? null,
    'alvo' => $_GET['alvo'] ?? null
];

$limite = isset($_GET['limite']) && in_array((int)$_GET['limite'], [10, 25, 50, 100])
    ? (int)$_GET['limite']
    : 10;

$ultimos_acessos = getUltimosLoginsFiltrados($pdo, $filtros, $limite);
?>

<?php template_header('Dashboard'); ?>

<h2>Dashboard Estatístico</h2>
<p><a href="painel_admin.php">Painel Administrativo</a> | <a href="painel.php">Painel</a> | <a href="logout.php">Sair</a></p>

<h3>Estatísticas Gerais</h3>
<ul>
    <li>Total de usuários: <strong><?= $estatisticas['total_usuarios'] ?></strong></li>
    <li>Total de administradores: <strong><?= $estatisticas['total_admins'] ?></strong></li>
    <li>Logins hoje: <strong><?= $estatisticas['total_logins_hoje'] ?></strong></li>
</ul>

<h3>Últimos usuários registrados</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>Usuário</th>
        <th>Email</th>
        <th>Registrado em</th>
    </tr>
    <?php foreach ($ultimos_usuarios as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= formataDataPtBr($u['criado_em']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h3>Filtrar Acessos</h3>
<form method="GET">
    <label>Data:</label>
    <input type="date" name="data" value="<?= htmlspecialchars($_GET['data'] ?? '') ?>">

    <label>Usuário:</label>
    <input type="text" name="usuario" value="<?= htmlspecialchars($_GET['usuario'] ?? '') ?>">

    <label>IP:</label>
    <input type="text" name="ip" value="<?= htmlspecialchars($_GET['ip'] ?? '') ?>">

    <label>Ação:</label>
    <input type="text" name="acao" value="<?= htmlspecialchars($_GET['acao'] ?? '') ?>">

    <label>Quantidade:</label>
    <select name="limite">
        <?php foreach ([10, 25, 50, 100] as $op): ?>
            <option value="<?= $op ?>" <?= $limite == $op ? 'selected' : '' ?>><?= $op ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filtrar</button>
</form>

<h3>Últimos Acessos</h3>

<?php renderizarTabelaLogs($ultimos_acessos); ?>

<?php template_footer(); ?>
