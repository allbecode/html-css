<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';
require_once '../includes/functions.php';
require_once '../includes/carros_functions.php';
require_once '../includes/header.php';

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'];

showAlert();

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
    <link rel="stylesheet" href="../assets/css/segmentation/modal.css">


    <style>
        .hidden {
            display: none;
        }

        #form-carro {
            margin-top: 15px;
            padding: 15px;
            /* border: 1px solid #ddd; */
            border-radius: 10px;
            /* background: #f9f9f9; */

            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.4s ease;

        }

        #form-carro.show {
            max-height: 600px;
            opacity: 1;
        }

        #form-carro > p {
            margin: 0 auto;
            margin-bottom: 20px;
        }


        button.btn-abrir {
            background: green;
            text-align: center;
            color: while;
            width: 50px;
            height: 50px;
            padding: 3px 8px;
            font-weight: 600;

            border: 2px solid green;
            border: none;
            border-radius: 50%;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        button.btn-abrir:hover {
            background: none;
            border: 2px solid green;
            color: green;
        }

        form p {
            grid-column: span 2;
            justify-self: center;

        }

        .container-btn-abrir p {
            margin-top: 10px;
            color: green;
        }

        .badge-carro {
            display: inline-block;
            min-width: 20px;
            min-height: 20px;
            padding: 2px 7px;
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
            vertical-align: middle;
            text-align: center;
            background: #e63946;
            color: #fff;
            line-height: 1;
        }



    </style>

    <script src="../assets//js//script-manutencoes.js" defer></script>
    <script src="../assets//js/script-carros.js" defer></script>

</head>

<body>
    <main>
        <h2>Cadastro de Carros</h2>

        <div class="container">

            <!-- Formulário de Cadastro -->

            <!-- <p>Por favor, preencha os campos abaixo:</p> -->

            <!-- Botão para abrir o formulário -->
            <div class="container-btn-abrir">
                <button
                    class="btn-abrir"
                    id="btn-novo-carro"
                    type="button">
                    +
                </button>
                <p>Novo Carro</p>
            </div>

            <!-- Formulário inicialmente oculto -->
            <form id="form-carro" method="post" action="../actions/add_carro.php" >
                
                    <p>Por favor, preencha os campos abaixo:</p>

                <input type="text" name="apelido" placeholder="Nome (opcional)">
                
                <input type="text" name="marca" required placeholder="Marca">

                <input type="text" name="modelo" required placeholder="Modelo">

                <select name="ano" id="ano" required>
                    <option value="">Ano</option>
                    <?php for ($a = (date('Y') - 60); $a <= (date('Y') + 1); $a++) { ?>
                        <option value="<?= $a ?>"><?= $a ?></option>
                    <?php } ?>
                </select>

                <input type="text" name="placa" maxlength="10" required placeholder="Placa">

                <input type="text" name="renavan" maxlength="20" placeholder="Renavam (opcional)">

                

                <button type="submit">Adicionar Carro</button>

            </form>


            <!-- Listagem de Carros -->
            <h3>Meus Carros</h3>
            <table>
                <thead>
                    <tr>
                        <th>Apelido</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Placa</th>
                        <th>Renavan</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carros as $carro): ?>
                        <?php if ($editId === (int)$carro['id']): ?>
                            <!-- Linha em modo edição -->
                            <form method="post" action="../actions/update_carro.php">
                                <tr>
                                    <td data-label="Nome">
                                        <input type="text" name="apelido" value="<?= esc($carro['apelido']) ?>">
                                    </td>

                                    <td data-label="Marca">
                                        <input type="text" name="marca" value="<?= esc($carro['marca']) ?>" required>
                                    </td>

                                    <td data-label="Modelo">
                                        <input type="text" name="modelo" value="<?= esc($carro['modelo']) ?>" required>
                                    </td>

                                    <td data-label="Ano">
                                        <input type="number" name="ano" value="<?= esc($carro['ano']) ?>" required>
                                    </td>

                                    <td data-label="Placa">
                                        <input type="text" name="placa" value="<?= esc($carro['placa']) ?>" required>
                                    </td>

                                    <td data-label="Renavan">
                                        <input type="text" name="renavan" value="<?= esc($carro['renavan']) ?>">
                                    </td>
                                    
                                    <td data-label="Ações">
                                        <input type="hidden" name="id" value="<?= $carro['id'] ?>">
                                        <button class="button-icon" type="submit" title="Salvar">💾</button>
                                        <a class="button-icon" href="cadastro_carros.php" title="Cancelar">❌</a>
                                    </td>
                                </tr>
                            </form>
                        <?php else: ?>
                            <!-- Linha normal -->
                            <tr data-carro-id="<?= (int)$carro['id']?>">
                                <td>
                                    <span class="badge-carro" id="badge-carro-<?= (int)$carro['id'] ?>" style="display:none" aria-hidden="true"></span>
                                </td>
                                <td data-label="Nome">
                                    <!-- <span class="badge-carro" id="badge-carro-<?= (int)$carro['id'] ?>" style="display:none" aria-hidden="true"></span> -->
                                    <?= esc($carro['apelido']) ?>
                                    
                                </td>
                                <td data-label="Marca"><?= esc($carro['marca']) ?></td>
                                <td data-label="Modelo"><?= esc($carro['modelo']) ?></td>
                                <td data-label="Ano"><?= esc($carro['ano']) ?></td>
                                <td data-label="Placa"><?= esc($carro['placa']) ?></td>
                                <td data-label="Renavan"><?= esc($carro['renavan']) ?></td>
                                <td data-label="Ações">
                                    <a class="button-icon" href="cadastro_carros.php?edit=<?= $carro['id'] ?>" title="Editar">✏️</a>

                                    <a class="button-icon" href="../actions/delete_carro.php?id=<?= $carro['id'] ?>"
                                        onclick="return confirm('Excluir este carro?')" title="Excluir">🗑️</a>

                                    <a class="button-icon"
                                        href="../pages/list_manutencoes.php?carro_id=<?= $carro['id'] ?>"
                                        title="Manutenções">🔧️</a>
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