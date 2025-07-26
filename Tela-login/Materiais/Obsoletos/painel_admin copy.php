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
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY criado_em DESC")->fetchAll();
?>

<h2>Painel Administrativo</h2>
<p><a href="painel.php">Voltar ao Painel</a> | <a href="logout.php">Sair</a></p>

<?php if ($msg): ?>
    <p><strong><?php echo $msg; ?></strong></p>
<?php endif; ?>

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
