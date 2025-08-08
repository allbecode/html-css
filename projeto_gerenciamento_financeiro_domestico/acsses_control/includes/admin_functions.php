<?php

require_once 'log_functions.php';

function exigirAdmin() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php");
        exit;
    }

    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT tipo FROM usuarios WHERE username = ?");
    $stmt->execute([$_SESSION['usuario']]);
    $user = $stmt->fetch();

    if (!$user || $user['tipo'] !== 'admin') {
        echo "<p style='color:red;'>Acesso restrito a administradores.</p>";
        exit;
    }
}

function buscarUsernamePorId(PDO $pdo, int $id): string {
    $stmt = $pdo->prepare("SELECT username FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: 'desconhecido';
}

function buscarUsuariosComFiltro(PDO $pdo, array $filtros): array {
    $sql = "SELECT * FROM usuarios WHERE 1=1";
    $params = [];

    if (!empty($filtros['usuario'])) {
        $sql .= " AND username LIKE ?";
        $params[] = '%' . $filtros['usuario'] . '%';
    }

    if (!empty($filtros['tipo'])) {
        $sql .= " AND tipo = ?";
        $params[] = $filtros['tipo'];
    }

    if (!empty($filtros['criado_em'])) {
        $sql .= " AND DATE(criado_em) = ?";
        $params[] = $filtros['criado_em'];
    }

    $sql .= " ORDER BY id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function atualizarTipoUsuario(PDO $pdo, array $dados): void {
    if ($dados['usuario_id'] == 1) return;

    $stmt = $pdo->prepare("UPDATE usuarios SET tipo = ? WHERE id = ?");
    $stmt->execute([$dados['novo_tipo'], $dados['usuario_id']]);
    echo 'Tipo de usuário alterado com sucesso!';
}

// function excluirUsuario(PDO $pdo, int $id): void {
//     if ($id == 1) return;

//     // Buscar dados do usuário antes de apagar
//     $stmt = $pdo->prepare("SELECT id, username FROM usuarios WHERE id = ?");
//     $stmt->execute([$id]);
//     $usuario = $stmt->fetch();

//         if ($usuario) {
//             excluirBancoUsuario($usuario['id'], $usuario['username']);
//         }

//         // Depois disso, delete o usuário normalmente

//     $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
//     $stmt->execute([$id]);

// }

function excluirUsuario(PDO $pdo, int $id): void {
    if ($id == 1) return; // Nunca exclua o usuário admin padrão

    // Excluir transações do usuário
    $stmt = $pdo->prepare("DELETE FROM transacoes WHERE usuario_id = ?");
    $stmt->execute([$id]);

    // Excluir dependentes do usuário
    $stmt = $pdo->prepare("DELETE FROM dependentes WHERE usuario_id = ?");
    $stmt->execute([$id]);

    // Excluir o próprio usuário
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}


function renderizarTabelaUsuarios(array $usuarios): void {
    $idLogado = $_SESSION['id'] ?? null;

    echo '<table border="1" cellpadding="5">
        <tr>
            <th>ID</th><th>Usuário</th><th>Nome</th><th>Email</th><th>Tipo</th>
            <th>Criado em</th><th>Último login</th><th>Ações</th>
        </tr>';

    foreach ($usuarios as $u) {
        $id = $u['id'];
        $username = htmlspecialchars($u['username']);
        $nome = htmlspecialchars($u['nome']) ?? 'n/a';
        $email = htmlspecialchars($u['email']);
        $tipo = $u['tipo'];
        $criadoEm = formataDataPtBr($u['criado_em']);
        $ultimoLogin = formataDataPtBr($u['ultimo_login'] ?? 'Nunca');

        echo '<tr>';
        echo "<td>$id</td>";
        echo "<td>$username</td>";
        echo "<td>$nome</td>";
        echo "<td>$email</td>";

        // Controle do campo tipo
        echo '<td>';
        if ($id === 1 || $id === $idLogado) {
            echo htmlspecialchars($tipo); // Sem select para si mesmo ou admin principal
        } else {
            echo '<form method="POST" style="display:inline;">
                <input type="hidden" name="usuario_id" value="' . $id . '">
                <input type="hidden" name="usuario" value="' . $username . '">
                <select name="novo_tipo" onchange="this.form.submit()">
                    <option value="usuario"' . ($tipo === 'usuario' ? ' selected' : '') . '>usuario</option>
                    <option value="admin"' . ($tipo === 'admin' ? ' selected' : '') . '>admin</option>
                </select>
                <input type="hidden" name="mudar_tipo" value="1">
            </form>';
        }
        echo '</td>';

        echo "<td>$criadoEm</td>";
        echo "<td>$ultimoLogin</td>";

        // Ação de exclusão
        echo '<td>';
        if ($id == 1) {
            echo '(admin principal)';
        } elseif ($id == $idLogado) {
            echo '(você)';
        } else {
            echo '<a href="?excluir=' . $id . '" onclick="return confirm(\'Tem certeza?\')">Excluir</a>';
        }
        echo '</td>';

        echo '</tr>';
    }

    echo '</table>';
}

