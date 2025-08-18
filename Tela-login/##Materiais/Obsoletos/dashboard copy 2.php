<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';
require_once 'session.php';
require_once 'auth.php';
require_once 'layout.php';

protegerPagina();

// Apenas admins podem acessar
if ($_SESSION['tipo'] !== 'admin') {
    header('Location: painel.php');
    exit;
}

$pdo = conectar();

// Coleta de estatísticas
$total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_admins = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'admin'")->fetchColumn();
$total_logins_hoje = $pdo->query("SELECT COUNT(*) FROM logs_acesso WHERE acao = 'login' AND DATE(data_hora) = CURDATE()")->fetchColumn();

$ultimos_usuarios = $pdo->query("SELECT username, email, criado_em FROM usuarios ORDER BY criado_em DESC LIMIT 5")->fetchAll();
$ultimos_logins = $pdo->query("SELECT username, data_hora FROM logs_acesso WHERE acao = 'login' ORDER BY data_hora DESC LIMIT 5")->fetchAll();

// Constrói condições dinamicamente
$condicoes = [];
$parametros = [];


if (!empty($_GET['data'])) {
    $condicoes[] = "DATE(data_hora) = ?";
    $parametros[] = $_GET['data'];
}

if (!empty($_GET['usuario'])) {
    $condicoes[] = "username LIKE ?";
    $parametros[] = '%' . $_GET['usuario'] . '%';
}

if (!empty($_GET['ip'])) {
    $condicoes[] = "ip LIKE ?";
    $parametros[] = '%' . $_GET['ip'] . '%';
}

if (!empty($_GET['acao'])) {
    $condicoes[] = "acao LIKE ?";
    $parametros[] = '%' . $_GET['acao'] . '%';
}

// Limite seguro
$limite = 10;
if (isset($_GET['limite']) && in_array((int)$_GET['limite'], [10, 25, 50, 100])) {
    $limite = (int)$_GET['limite'];
}

//Query
$sql = "SELECT username, ip, acao, data_hora FROM logs_acesso";

if (!empty($condicoes)) {
    $sql .= " WHERE " . implode(" AND ", $condicoes);
}

$sql .= " ORDER BY data_hora DESC LIMIT $limite";

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);
$ultimos_acessos = $stmt->fetchAll();

?>
<?php template_header('Dashboard');?>

    <h2>Dashboard Estatístico</h2>
    <p><a href="painel_admin.php">Painel Administrativo</a> | <a href="painel.php">Painel</a> | <a href="logout.php">Sair</a></p>

    <h3>Estatísticas Gerais</h3>
    <ul>
        <li>Total de usuários: <strong><?php echo $total_usuarios; ?></strong></li>
        <li>Total de administradores: <strong><?php echo $total_admins; ?></strong></li>
        <li>Logins hoje: <strong><?php echo $total_logins_hoje; ?></strong></li>
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
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo $u['criado_em']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Filtrar Acessos por Data</h3>
    <form method="GET">
        <label>Data:</label>
        
        <!-- <input type="date" name="data" value="<?php echo $_GET['data'] ?? date('Y-m-d'); ?>"> --> <!--NÃO APAGAR-->

         <input type="date" name="data" value="<?php echo $_GET['data'] ?? '' ?>">

        <label>Usuário:</label>
        <input type="text" name="usuario" value="<?php echo $_GET['usuario'] ?? ''; ?>">

        <label>IP:</label>
        <input type="text" name="ip" value="<?php echo $_GET['ip'] ?? ''; ?>">

        <label>Ação:</label>
        <select name="acao" id="">
            <option value="">...</option>
            <option value="login">Login</option>
            <option value="logout">Logout</option>
        </select>

        <label>Quantidade:</label>
        <select name="limite">
            <option value="10" <?php if (isset($_GET['limite']) && $_GET['limite'] == 10) echo 'selected'; ?>>10</option>
            <option value="25" <?php if (isset($_GET['limite']) && $_GET['limite'] == 25) echo 'selected'; ?>>25</option>
            <option value="50" <?php if (isset($_GET['limite']) && $_GET['limite'] == 50) echo 'selected'; ?>>50</option>
            <option value="100" <?php if (isset($_GET['limite']) && $_GET['limite'] == 100) echo 'selected'; ?>>100</option>
        </select>


        <button type="submit">Filtrar</button>
    </form>

    <h3>Últimos Acesos (logins / logouts)</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Usuário</th>
            <th>IP</th>
            <th>Ação</th>
            <th>Data/Hora</th>
        </tr>
        <?php foreach ($ultimos_acessos as $l): ?>
            <tr>
                <td><?php echo htmlspecialchars($l['username']); ?></td>
                <td><?php echo htmlspecialchars($l['ip']); ?></td>
                <td><?php echo strtoupper($l['acao']); ?></td>
                <td><?php echo $l['data_hora']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php template_footer();?>