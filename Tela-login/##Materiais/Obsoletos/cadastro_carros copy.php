<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'];

// verificar se existe carro em edição
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;

// Buscar carros cadastrados do usuário
$stmt = $pdo->prepare("SELECT * FROM carros WHERE usuario_id = :usuario_id ORDER BY criado_em ASC");
$stmt->execute(['usuario_id' => $usuarioId]);
$carros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeFinD - Cad Car</title>

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <!-- <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/segmentation/form-global.css"> -->
     <link rel="stylesheet" href="../assets/css/segmentation/carros.css">
</head>

<body>
    <main>
        <h2>Cadastro de Carros</h2>

        <div class="container">
            
            <!-- Formulário de Cadastro -->

            <p>Por favor, preencha os campos abaixo:</p>

            <form method="post" action="../actions/add_carro.php" class="form-geral">
                <!-- <label>Marca:</label> -->  
                <input type="text" name="marca" required placeholder="Marca">

                <!-- <label>Modelo:</label> -->
                <input type="text" name="modelo" required placeholder="Modelo">

                <!-- <label>Ano:</label> -->
                <input type="number" name="ano" min="1900" max="<?= date('Y') + 1 ?>" required placeholder="Ano">

                <!-- <label>Placa:</label> -->
                <input type="text" name="placa" maxlength="10" required placeholder="Placa">

                <!-- <label>Renavan:</label> -->
                <input type="text" name="renavan" maxlength="20" placeholder="Renava (opcional)">

                <!-- <label>Apelido (opcional):</label> -->
                <input type="text" name="apelido" placeholder="Apelido (opcional)">

                <button type="submit">Adicionar Carro</button>
            </form>

            <!-- <hr> -->

            <!-- Listagem de Carros -->
            <h3>Meus Carros</h3>
            <table>
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Placa</th>
                        <th>Renavan</th>
                        <th>Apelido</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carros as $c): ?>
                        <?php if ($editId === (int)$c['id']): ?>
                            <!-- Linha em modo edição -->
                            <tr>
                                <form method="post" action="../actions/update_carro.php">
                                    <td data-label="Marca"><input type="text" name="marca" value="<?= esc($c['marca']) ?>" required></td>
                                    <td data-label="Modelo"><input type="text" name="modelo" value="<?= esc($c['modelo']) ?>" required></td>
                                    <td data-label="Ano"><input type="number" name="ano" value="<?= esc($c['ano']) ?>" required></td>
                                    <td data-label="Placa"><input type="text" name="placa" value="<?= esc($c['placa']) ?>" required></td>
                                    <td data-label="Renavan"><input type="text" name="renavan" value="<?= esc($c['renavan']) ?>"></td>
                                    <td data-label="Apelido"><input type="text" name="apelido" value="<?= esc($c['apelido']) ?>"></td>
                                    <td data-label="Ações">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <button class="button-icon" type="submit" title="Salvar">💾</button>
                                        <button class="button-icon" ><a href="cadastro_carros.php" title="Cancelar">❌</a></button>
                                    </td>
                                </form>
                            </tr>
                        <?php else: ?>
                            <!-- Linha normal -->
                            <tr>
                                <td data-label="Marca"><?= esc($c['marca']) ?></td>
                                <td data-label="Modelo"><?= esc($c['modelo']) ?></td>
                                <td data-label="Ano"><?= esc($c['ano']) ?></td>
                                <td data-label="Placa"><?= esc($c['placa']) ?></td>
                                <td data-label="Renavan"><?= esc($c['renavan']) ?></td>
                                <td data-label="Apelido"><?= esc($c['apelido']) ?></td>
                                <td data-label="Ações">
                                    <button class="button-icon" title="Editar"><a href="cadastro_carros.php?edit=<?= $c['id'] ?>">✏️</a></button>
                                    <button class="button-icon" title="Excluir"><a href="../actions/delete_carro.php?id=<?= $c['id'] ?>" onclick="return confirm('Excluir este carro?')">🗑️</a></button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>

</html>