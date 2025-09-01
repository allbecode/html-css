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
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['modelo']);
    $ano = $_POST['ano'];
    $placa = strtoupper(trim($_POST['placa']));
    $renavan = isset($_POST['renavan']) && $_POST['renavan'] !== '' ? trim($_POST['renavan']) : null;
    $apelido = isset($_POST['apelido']) && $_POST['apelido'] !== '' ? trim($_POST['apelido']) : null;

    try {
        $resultado = verificarDuplicidadeCarro($pdo, $usuarioId, $placa, $renavan);

        if ($resultado['status'] === 'error') {
            setAlert($resultado['mensagem'], "error");

        } else {
            $stmt = $pdo->prepare("INSERT INTO carros 
                (usuario_id, marca, modelo, ano, placa, renavan, apelido) 
                VALUES (:usuario_id, :marca, :modelo, :ano, :placa, :renavan, :apelido)");

            $stmt->execute([
                'usuario_id' => $usuarioId,
                'marca'      => $marca,
                'modelo'     => $modelo,
                'ano'        => $ano,
                'placa'      => $placa,
                'renavan'    => $renavan,
                'apelido'    => $apelido
            ]);

            if ($resultado['status'] === 'warning') {
                setAlert($resultado['mensagem'], "warning");
            } else {
                setAlert("Carro cadastrado com sucesso.", "success");
            }
        }
    } catch (PDOException $e) {
        setAlert("Erro ao cadastrar o carro: " . $e->getMessage(), "error");
    }
}

header("Location: ../pages/cadastro_carros.php");
exit;

