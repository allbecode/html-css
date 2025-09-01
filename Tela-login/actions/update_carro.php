<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../acsses_control/includes/auth.php";
require_once "../acsses_control/includes/db.php";
require_once '../acsses_control/includes/session.php';
require_once __DIR__ . '/../includes/carros_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioId = $_SESSION['usuario_id'];
    $id = $_POST['id'] ?? null;

    // if (!$id) {
    //     $_SESSION['carro_mensagem'] = "ID do carro não informado.";
    //     $_SESSION['carro_tipo'] = "error";
    //     header("Location: ../pages/cadastro_carros.php");
    //     exit;
    // }

    $marca   = trim($_POST['marca']);
    $modelo  = trim($_POST['modelo']);
    $ano     = (int) $_POST['ano']; // garante inteiro
    $placa   = strtoupper(trim($_POST['placa']));
    // $renavan = !empty($_POST['renavan']) ? trim($_POST['renavan']) : null;
    // $apelido = !empty($_POST['apelido']) ? trim($_POST['apelido']) : null;
    $renavan = isset($_POST['renavan']) && $_POST['renavan'] !== '' ? trim($_POST['renavan']) : null;
    $apelido = isset($_POST['apelido']) && $_POST['apelido'] !== '' ? trim($_POST['apelido']) : null;

    try {
        // Verificar duplicidade (ignora o próprio carro pelo ID)
        $resultado = verificarDuplicidadeCarro($pdo, $usuarioId, $placa, $renavan, $id);

        if ($resultado['status'] === 'error') {
            setAlert($resultado['mensagem'], 'error');
        } else {
            // error_log("Atualizando carro ID: $id do usuário: $usuarioId");

            $stmt = $pdo->prepare("
                UPDATE carros 
                   SET marca = :marca, 
                       modelo = :modelo, 
                       ano = :ano, 
                       placa = :placa, 
                       renavan = :renavan, 
                       apelido = :apelido
                 WHERE id = :id AND usuario_id = :usuario_id
            ");

            $stmt->execute([
                ':marca'      => $marca,
                ':modelo'     => $modelo,
                ':ano'        => $ano,
                ':placa'      => $placa,
                ':renavan'    => $renavan,
                ':apelido'    => $apelido,
                ':id'         => $id,
                ':usuario_id' => $usuarioId
            ]);

            if ($stmt->rowCount() > 0) {
                setAlert('Carro atualizado com sucesso!', 'success');
            } else {
                // Se não houve alteração, ainda consideramos sucesso se a query rodou sem erro
                setAlert('Nenhuma alteração detectada (dados iguais aos já salvos.', 'warning');
            }
        }
    } catch (PDOException $e) {
        setAlert("Erro ao atualizar o carro: " . $e->getMessage(), 'error');
    }

    // $_SESSION['carro_mensagem'] = $mensagem;
    // $_SESSION['carro_tipo'] = $tipoMensagem;

    header("Location: ../pages/cadastro_carros.php");
    exit;
}
