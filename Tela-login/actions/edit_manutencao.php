<?php
require_once '../acsses_control/includes/db.php';

$id = $_POST['id'] ?? 0;
$tipo = $_POST['tipo'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$data = $_POST['data'] ?? '';
$km = $_POST['km'] ?? null;
$valor = $_POST['valor'] ?? null;
$pago = $_POST['pago'] ?? 0;
$proxima_data = $_POST['proxima_manut_data'] ?? null;
$proxima_km = $_POST['proxima_manut_km'] ?? null;

try {
    $stmt = $pdo->prepare("UPDATE manutencoes_carro 
        SET tipo=?, descricao=?, data=?, km=?, valor=?, pago=?, proxima_manut_data=?, proxima_manut_km=?
        WHERE id=?");
    $stmt->execute([$tipo, $descricao, $data, $km, $valor, $pago, $proxima_data, $proxima_km, $id]);

    echo "success";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

// require_once '../acsses_control/includes/db.php';

// header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $id = intval($_POST['id']);
//     $data = $_POST['data'] ?? null;
//     $tipo = $_POST['tipo'] ?? null;
//     $km = $_POST['km'] ?? null;
//     $valor = $_POST['valor'] ?? null;
//     $pago = $_POST['pago'] ?? 0;
//     $proxima_manut_data = $_POST['proxima_manut_data'] ?? null;
//     $proxima_manut_km = $_POST['proxima_manut_km'] ?? null;
//     $descricao = $_POST['descricao'] ?? null;

//     try {
//         $stmt = $pdo->prepare("
//             UPDATE manutencoes_carro
//             SET 
//                 data = :data,
//                 tipo = :tipo,
//                 km = :km,
//                 valor = :valor,
//                 pago = :pago,
//                 proxima_manut_data = :proxima_manut_data,
//                 proxima_manut_km = :proxima_manut_km,
//                 descricao = :descricao
//             WHERE id = :id
//         ");

//         $stmt->execute([
//             ':data' => $data,
//             ':tipo' => $tipo,
//             ':km' => $km,
//             ':valor' => $valor,
//             ':pago' => $pago,
//             ':proxima_manut_data' => $proxima_manut_data,
//             ':proxima_manut_km' => $proxima_manut_km,
//             ':descricao' => $descricao,
//             ':id' => $id
//         ]);

//         echo json_encode(["sucesso" => true]);
//     } catch (Exception $e) {
//         echo json_encode(["sucesso" => false, "mensagem" => $e->getMessage()]);
//     }
// } else {
//     echo json_encode(["sucesso" => false, "mensagem" => "Método inválido"]);
// }

