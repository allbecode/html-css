<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/session.php';
require_once '../includes/usuario.php';
require_once '../includes/functions.php';
require_once '../includes/log_functions.php';
require_once '../includes/header-login.php';

verificaUsuarioLogado();

$msg = '';
$usuario = buscarUsuarioPorUsername($_SESSION['usuario']);
$primeiro_nome = explode(' ', trim($_SESSION['nome'] ?? $_SESSION['usuario']))[0];


if (isset($_SESSION['mensagem_sucesso'])) {
    echo "<div class='mensagem sucesso'>{$_SESSION['mensagem_sucesso']}</div>";
    unset($_SESSION['mensagem_sucesso']);
}

if (isset($_SESSION['mensagem_erro'])) {
    echo "<div class='mensagem erro'>{$_SESSION['mensagem_erro']}</div>";
    unset($_SESSION['mensagem_erro']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['atualizar_email'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "Email inválido!";
        } else {
            $msg = atualizarEmail($usuario['id'], $email);
            if ($msg === true) {
                $usuario['email'] = $email;
                registrarOperacao('atualizou email', $usuario['username']);
                $msg = "<p style='color:green;'>E-mail atualizado com sucesso!</p>";
            }
        }
    }

    if (isset($_POST['atualizar_dados'])) {
        $nome = trim($_POST['nome']);
        $sobrenome = trim($_POST['sobrenome']);
        $data_nasc = $_POST['data_nascimento'];
        if (atualizarNomeCompleto($usuario['id'], $nome, $sobrenome, $data_nasc)) {
            $_SESSION['nome'] = $nome;
            $usuario['nome'] = $nome;
            $usuario['sobrenome'] = $sobrenome;
            $usuario['data_nascimento'] = $data_nasc;
            registrarOperacao('atualizou dados', $usuario['username']);
            
            header("Location: perfil.php");
            exit;

            $msg = 'Dados atualizados com sucesso!';
        }
    }

    if (isset($_POST['alterar_senha'])) {
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];
        if (password_verify($senha_atual, $usuario['senha'])) {
            atualizarSenha($usuario['id'], $nova_senha);
            $msg = "Senha alterada com sucesso!";
            registrarOperacao('alterou senha', $usuario['username']);
        } else {
            $msg = "Senha atual incorreta!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFind - Painel Admin</title>

    <link rel="stylesheet" href="../../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../../assets/css/segmentation/form-global.css">
    <link rel="stylesheet" href="../../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>
    <main>

        <h2>Meu Perfil</h2>

        <p><?= $msg ?></p>

        <div class="dashboard-container">
            <div class="dashboard-card dados">
                <h1><?php echo saudacao($primeiro_nome)?></h1>
                <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome'] . " " . $usuario['sobrenome']) ?></p>
                <p><strong>Usuário:</strong> <?= htmlspecialchars($usuario['username']) ?></p>
                <p><strong>Tipo:</strong> <?= $usuario['tipo'] ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
                <p><strong>Último login:</strong> <?= formataDataPtBr($usuario['ultimo_login']) ?? 'Nunca' ?></p>
            </div>
            <!-- <hr> -->
            <div class="dashboard-card">
                <h3>Atualizar Email</h3>
                <form method="POST">
                    <label for="email">Novo Email: </label>
                    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br><br>
                    <button type="submit" name="atualizar_email">Atualizar Email</button>
                </form>
            </div>
            <!-- <hr> -->
            <div class="dashboard-card">
                <h3>Alterar Senha</h3>
                <form method="POST">
                    <label for="senha_atual">Senha Atual: </label>
                    <input type="password" name="senha_atual" required><br><br>
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" name="nova_senha" required><br><br>
                    <button type="submit" name="alterar_senha">Atualizar Senha</button>
                </form>
            </div>
        </div>
        <!-- <hr> -->
        <div class="dashboard-container">
            <!-- <div class="dashboard-container"> -->
            <div class="dashboard-card">
                <h2>Editar Informações</h2>
                <form method="POST">
                    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>"><br><br>
                    Sobrenome: <input type="text" name="sobrenome" value="<?= htmlspecialchars($usuario['sobrenome'] ?? '') ?>"><br><br>
                    Data de Nascimento: <input type="date" name="data_nascimento" value="<?= $usuario['data_nascimento'] ?? '' ?>"><br><br>
                    <button type="submit" name="atualizar_dados">Atualizar Informações</button>
                </form>
            </div>


            <div class="dashboard-card">
                <section>
                    <h3>Cadastrar Dependentes</h3>
                    <form method="post" action="../actions/salvar_dependente.php">
                        <input type="hidden" name="dependente_id" value="">
                        <label for="nome_dependente">Nome:</label>
                        <input type="text" name="nome" id="nome_dependente" required>
                        <label for="data_nascimento">Data de Nascimento:</label>
                        <input type="date" name="data_nascimento" id="data_nascimento">
                        <label for="relacionamento">Relacionamento:</label>
                        <select name="relacionamento" id="relacionamento">
                            <option value="">Selecione...</option>
                            <option value="Cônjuge">Cônjuge</option>
                            <option value="Filho">Filho</option>
                            <option value="Filha">Filha</option>
                        </select>
                        <button type="submit" name="salvar_dependente">Salvar Dependente</button>
                    </form>
                </section>
                <?php
                $dependentes = buscarDependentesPorUsuarioId($usuario['id']);
                if (!empty($dependentes)):
                ?>

                    <h3>Lista de Dependentes</h3>
                    <!-- <table class="tabela-dependentes" border="1" cellpadding="5"> -->
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Nascimento</th>
                                <th>Relacionamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dependentes as $dep): ?>
                                <tr>
                                    <td><?= htmlspecialchars($dep['nome']) ?></td>
                                    <td><?= formataDataPtBr($dep['data_nascimento']) ?></td>
                                    <td><?= htmlspecialchars($dep['relacionamento']) ?></td>
                                    <td>
                                        <form method="post" action="../actions/excluir_dependente.php" style="display:inline;">
                                            <input type="hidden" name="dependente_id" value="<?= $dep['id'] ?>">
                                            <input type="hidden" name="nome" value="<?= $dep['nome'] ?>">
                                            <button class="excluir" type="submit" onclick="return confirm('Deseja realmente excluir este dependente?')">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                <?php endif ?>
            </div>
            <!-- </div> -->
        </div>
    </main>

</body>

</html>