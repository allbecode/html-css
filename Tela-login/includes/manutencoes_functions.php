<?php
require_once __DIR__ . '/../acsses_control/includes/db.php';
require_once __DIR__ . '/../acsses_control/includes/functions.php';

function cadastrarManutencao(PDO $pdo, int $usuarioId, int $carroId, array $dados): array
{
    try {
        // Verificar se o carro existe e pertence ao usuário
        $stmt = $pdo->prepare("SELECT id FROM carros WHERE id = :carro_id AND usuario_id = :usuario_id");
        $stmt->execute([
            'carro_id' => $carroId,
            'usuario_id' => $usuarioId
        ]);
        if (!$stmt->fetch()) {
            echo "Erro: carro não encontrado ou não pertence a este usuário.";
            exit;
        }



        $stmt = $pdo->prepare("INSERT INTO manutencoes_carro 
            (carro_id, usuario_id, tipo, descricao, data, km, valor, pago, proxima_manut_data, proxima_manut_km) 
            VALUES (:carro_id, :usuario_id, :tipo, :descricao, :data, :km, :valor, :pago, :proxima_data, :proxima_km)");

        $stmt->execute([
            'carro_id'      => $carroId,
            'usuario_id'    => $usuarioId,
            'tipo'          => $dados['tipo'],
            'descricao'     => $dados['descricao'] ?? null,
            'data'          => $dados['data'],
            'km'            => $dados['km'] ?? null,
            'valor'         => $dados['valor'] ?? null,
            'pago'          => $dados['pago'] ?? 0,
            'proxima_data'  => $dados['proxima_manut_data'] ?? null,
            'proxima_km'    => $dados['proxima_manut_km'] ?? null,
        ]);

        return ['status' => 'success', 'mensagem' => 'Manutenção cadastrada com sucesso!'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'mensagem' => 'Erro ao cadastrar manutenção: ' . $e->getMessage()];
    }
}

function listarManutencoes(PDO $pdo, int $usuarioId, int $carroId): array
{
    $stmt = $pdo->prepare("SELECT * FROM manutencoes_carro 
                           WHERE usuario_id = :usuario_id AND carro_id = :carro_id 
                           ORDER BY data DESC");
    $stmt->execute(['usuario_id' => $usuarioId, 'carro_id' => $carroId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
