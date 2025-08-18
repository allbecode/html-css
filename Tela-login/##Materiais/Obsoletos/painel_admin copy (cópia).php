<?php
require 'functions.php';
session_start();

// Verifica se está logado e se é admin
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pdo = conectar();
$msg = "";

// Exclusão
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    if ($id == 1) {
        $msg = "Você não pode excluir o admin principal.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Usuário excluído com sucesso.";
    }
}

// Alterar tipo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mudar_tipo'])) {
    $id = intval($_POST['usuario_id']);
    $novo_tipo = $_POST['novo_tipo'];

    $stmt = $pdo->prepare("UPDATE usuarios SET tipo = ? WHERE id = ?");
    $stmt->execute([$novo_tipo, $id]);
    $msg = "Tipo de usuário atualizado.";
}

// Buscar usuários
// $usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY criado_em DESC")->fetchAll();

$condicoes = [];
$params = [];

if (!empty($_GET['usuario'])) {
    $condicoes[] = "username LIKE ?";
    $params[] = '%' . $_GET['usuario'] . '%';
}

if (!empty($_GET['tipo']) && in_array($_GET['tipo'], ['admin', 'usuario'])) {
    $condicoes[] = "tipo = ?";
    $params[] = $_GET['tipo'];
}

if (!empty($_GET['data'])) {
    $condicoes[] = "DATE(criado_em) = ?";
    $params[] = $_GET['data'];
}

$sql = "SELECT id, username, email, tipo, criado_em, ultimo_login FROM usuarios";
if ($condicoes) {
    $sql .= " WHERE " . implode(" AND ", $condicoes);
}
$sql .= " ORDER BY criado_em DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll();

?>

<h2>Painel Administrativo</h2>
<p><a href="painel.php">Voltar ao Painel</a> | <a href="logout.php">Sair</a></p>

<?php if ($msg): ?>
    <p><strong><?php echo $msg; ?></strong></p>
<?php endif; ?>

<form method="GET" style="margin-bottom: 20px;">
    <label>Usuário:</label>
    <input type="text" name="usuario" value="<?php echo htmlspecialchars($_GET['usuario'] ?? '') ?>">

    <label>Tipo:</label>
    <select name="tipo">
        <option value="">Todos</option>
        <option value="admin" <?php if (($_GET['tipo'] ?? '') == 'admin') echo 'selected'; ?>>admin</option>
        <option value="usuario" <?php if (($_GET['tipo'] ?? '') == 'usuario') echo 'selected'; ?>>usuario</option>
    </select>

    <label>Data de Criação:</label>
    <input type="date" name="data" value="<?php echo htmlspecialchars($_GET['data'] ?? '') ?>">

    <button type="submit">Filtrar</button>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Usuário</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Criado em</th>
        <th>Último login</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="usuario_id" value="<?php echo $u['id']; ?>">
                    <select name="novo_tipo" onchange="this.form.submit()" <?php echo $u['id'] == 1 ? 'disabled' : ''; ?>>
                        <option value="usuario" <?php echo $u['tipo'] === 'usuario' ? 'selected' : ''; ?>>usuario</option>
                        <option value="admin" <?php echo $u['tipo'] === 'admin' ? 'selected' : ''; ?>>admin</option>
                    </select>
                    <input type="hidden" name="mudar_tipo" value="1">
                </form>
            </td>
            <td><?php echo $u['criado_em']; ?></td>
            <td><?php echo $u['ultimo_login'] ?? 'Nunca'; ?></td>
            <td>
                <?php if ($u['id'] != 1): ?>
                    <a href="?excluir=<?php echo $u['id']; ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                <?php else: ?>
                    (admin principal)
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
