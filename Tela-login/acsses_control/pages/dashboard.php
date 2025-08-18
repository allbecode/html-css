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
require_once '../includes/header-login.php';

verificaUsuarioLogado();

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


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFinD - Dashboard</title>

    <link rel="stylesheet" href="../../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../../assets/css/segmentation/form-global.css">
    <link rel="stylesheet" href="../../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/segmentation/relatorio-contribuicao.css">
</head>

<body>
    <main>

<h2>Dashboard Estatístico</h2>
        <div class="dashboard-container">
            
            <!-- <p><a href="painel_admin.php">Painel Administrativo</a> | <a href="painel.php">Painel</a> | <a href="logout.php">Sair</a></p> -->
            <div class="dashboard-card">
                <h2>Estatísticas Gerais</h2>
                
                    <p>Total de usuários: <strong><?= $estatisticas['total_usuarios'] ?></strong></p>
                    <p>Total de administradores: <strong><?= $estatisticas['total_admins'] ?></strong></p>
                    <p>Logins hoje: <strong><?= $estatisticas['total_logins_hoje'] ?></strong></p>
                
            </div>
             <div class="dashboard-card">
            <h3>Últimos usuários registrados</h3>
           
                <!-- <table border="1" cellpadding="5"> -->
                <table>
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Registrado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimos_usuarios as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= formataDataPtBr($u['criado_em']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h2>Filtrar Acessos</h2>
            <form method="GET" class="form-geral">
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
        </div>
        <h2>Últimos Acessos</h2>
        <div class="dashboard-card">
            <?php renderizarTabelaLogs($ultimos_acessos); ?>
        </div>

    </main>
</body>

</html>