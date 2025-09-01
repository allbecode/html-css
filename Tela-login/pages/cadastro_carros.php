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

    <script src="../assets//js//script-manutencoes.js" defer></script>

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
                <select name="ano" id="ano" required>
                    <option value="">Ano</option>
                    <?php for ($a = (date('Y') - 60); $a <= (date('Y') + 1); $a++) {; ?>
                        <option value="<?= $a ?>"><?= $a ?></option>
                    <?php }; ?>
                </select>
                <!-- <label>Placa:</label> -->
                <input type="text" name="placa" maxlength="10" required placeholder="Placa">

                <!-- <label>Renavan:</label> -->
                <input type="text" name="renavan" maxlength="20" placeholder="Renava (opcional)">

                <!-- <label>Apelido (opcional):</label> -->
                <input type="text" name="apelido" placeholder="Apelido (opcional)">

                <button type="submit">Adicionar Carro</button>
            </form>

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
                    <?php foreach ($carros as $carro): ?>
                        <?php if ($editId === (int)$carro['id']): ?>
                            <!-- Linha em modo edição -->
                            <form method="post" action="../actions/update_carro.php">
                                <tr>
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
                                    <td data-label="Apelido">
                                        <input type="text" name="apelido" value="<?= esc($carro['apelido']) ?>">
                                    </td>
                                    <td data-label="Ações">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <button class="button-icon" type="submit" title="Salvar">💾</button>
                                        <a class="button-icon" href="cadastro_carros.php" title="Cancelar">❌</a>
                                    </td>
                                </tr>
                            </form>
                        <?php else: ?>
                            <!-- Linha normal -->
                            <tr>
                                <td data-label="Marca"><?= esc($carro['marca']) ?></td>
                                <td data-label="Modelo"><?= esc($carro['modelo']) ?></td>
                                <td data-label="Ano"><?= esc($carro['ano']) ?></td>
                                <td data-label="Placa"><?= esc($carro['placa']) ?></td>
                                <td data-label="Renavan"><?= esc($carro['renavan']) ?></td>
                                <td data-label="Apelido"><?= esc($carro['apelido']) ?></td>
                                <td data-label="Ações">
                                    <a class="button-icon" href="cadastro_carros.php?edit=<?= $carro['id'] ?>" title="Editar">✏️</a>
                                    <a class="button-icon" href="../actions/delete_carro.php?id=<?= $carro['id'] ?>"
                                        onclick="return confirm('Excluir este carro?')" title="Excluir">🗑️</a>


                                    <!-- Botão dentro do card do carro -->
                                    <button class="btn-manutencoes button-icon" data-carro-id="<?= $carro['id'] ?>" title="Manutenções">🔧️</button>

                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </main>

    <!-- Modal de cadastro de manutenção -->
    <div id="modalManutencoes" class="modal">

        <div class="modal-content">

            <span class="close" id="closeModalNova">&times;</span>

            <h3>Manutenções do Carro</h3>

            <!-- <span class="close" id="closeModalNova">&times;</span> -->

            <!-- Formulário -->
            <form id="formManutencao" class="form-grid">
                <div class="grid-2cols">



                    <!-- Linha 1 -->

                    <input type="hidden" name="carro_id" id="carro_id">

                    <div>
                        <label for="data">Data:</label>
                        <input type="date" name="data" id="data" required>
                    </div>

                    <div>
                        <label for="tipo">Tipo:</label>
                        <select name="tipo" id="tipo_manutencao" required></select>
                    </div>

                    <!-- Linha 2 -->

                    <div>
                        <label for="km">KM:</label>
                        <input type="number" name="km" id="km">
                    </div>

                    <div>
                        <label for="valor">Valor:</label>
                        <input type="number" step="0.01" name="valor" id="valor">
                    </div>

                    <!-- Linha 3 -->

                    <div>
                        <label for="proxima_manut_data">Próxima manut. (Data):</label>
                        <input type="date" name="proxima_manut_data" id="proxima_manut_data">
                    </div>

                    <div>
                        <label for="proxima_manut_km">Próxima manut. (KM):</label>
                        <input type="number" name="proxima_manut_km" id="proxima_manut_km">
                    </div>

                    <!-- Linha 4 -->

                    <div>
                        <label for="pago">Pago:</label>
                        <select name="pago" id="pago">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>

                    <div class="full-width">
                        <label for="descricao">Descrição:</label>
                        <textarea name="descricao" id="descricao"></textarea>
                    </div>
                </div>

                <!-- Botão -->

                <div class="form-actions">
                    <button type="submit">Salvar</button>
                </div>
            </form>

            <!-- Histórico -->

            <div class="hitorico">

                <h3>Histórico de Manutenções</h3>

                <div id="listaManutencoes">
                    <!-- Lista/Tabela de histórico renderizada dinamicamente -->
                </div>
            </div>

        </div>
    </div>

    <!-- Modal de edição -->
    <div id="modalEditarManutencao" class="modal">

        <div class="modal-content">

            <span class="close" id="closeModalEditar">&times;</span>

            <h2>Editar Manutenção</h2>

            <!-- Formulário -->

            <form id="formEditarManutencao" class="form-grid">
                <div class="grid-2cols">
                    <input type="hidden" name="id" id="editar_id">

                    <!-- Linha 1 -->
                    <div>
                        <label for="editar_data">Data</label>
                        <input type="date" name="data" id="editar_data" required>

                        <label for="editar_tipo">Tipo</label>
                        <select name="tipo" id="editar_tipo" required></select>
                    </div>

                    <!-- Linha 2 -->
                    <div>
                        <label for="editar_km">Km</label>
                        <input type="number" name="km" id="editar_km">

                        <label for="editar_valor">Valor</label>
                        <input type="number" step="0.01" name="valor" id="editar_valor">
                    </div>

                    <div>
                        <label for="editar_proxima_data">Próxima Manutenção (Data)</label>
                        <input type="date" name="proxima_manut_data" id="editar_proxima_data">

                        <label for="proxima_manut_km">Próxima Manutenção (Km)</label>
                        <input type="number" name="proxima_manut_km" id="editar_proxima_km">
                    </div>

                    <!-- Linha 4 -->
                    <div>
                        <label for="editar_pago">Pago?</label>
                        <select name="pago" id="editar_pago">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>

                    <div class="full-width">
                        <label for="editar_descricao">Descrição</label>
                        <textarea name="descricao" id="editar_descricao"></textarea>
                    </div>
                </div>

                <!-- Botão -->
                <div class="form-actions">
                    <button type="submit">Salvar Alterações</button>
                    <a href="" ></a>
                </div>
            </form>

        </div>
    </div>



    <?php include '../includes/footer.php'; ?>

</body>

</html>