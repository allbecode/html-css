<?php
require_once __DIR__ . '/../acsses_control/includes/db.php';
require_once __DIR__ . '/../acsses_control/includes/functions.php';

// NOVAS FUNÇÕES (NOVA MODELAGEM) ---------------------------------------------

// Adicionar novas manutenções
function cadastrarManutencao(PDO $pdo, int $usuarioId, int $carroId, array $dados): array
{
    try {
        // Verificar se o carro existe e pertence ao usuário
        $stmt = $pdo->prepare("
            SELECT id 
            FROM carros 
            WHERE id = :carro_id AND usuario_id = :usuario_id
        ");
        $stmt->execute([
            'carro_id'      => $carroId,
            'usuario_id'    => $usuarioId
        ]);
        if (!$stmt->fetch()) {
            return ['status' => 'error', 'mensagem' => 'Carro não encontrado ou não pertence a este usuário.'];
        }

        // Normalizar campos opcionais -> NULL se vierem vazios
        $dadosNormalizados = [
            'carro_id'           => $carroId,
            'usuario_id'         => $usuarioId,
            'tipo_id'            => $dados['tipo_id'] ?? null,
            'descricao'          => !empty($dados['descricao']) ? $dados['descricao'] : null,
            'data'               => $dados['data'] ?? null,
            'km'                 => !empty($dados['km']) ? $dados['km'] : null,
            'valor'              => !empty($dados['valor']) ? $dados['valor'] : null,
            'local'              => !empty($dados['local']) ? $dados['local'] : null,
            'pago'               => isset($dados['pago']) ? 1 : 0,
            'proxima_manut_data' => !empty($dados['proxima_manut_data']) ? $dados['proxima_manut_data'] : null,
            'proxima_manut_km'   => !empty($dados['proxima_manut_km']) ? $dados['proxima_manut_km'] : null,
        ];

        $stmt = $pdo->prepare("
            INSERT INTO manutencoes_carro 
            (carro_id, usuario_id, tipo_id, descricao, data, km, valor, local, pago, proxima_manut_data, proxima_manut_km) 
            VALUES (:carro_id, :usuario_id, :tipo_id, :descricao, :data, :km, :valor, :local, :pago, :proxima_manut_data, :proxima_manut_km)
        ");

        $stmt->execute($dadosNormalizados);

        


        return ['status' => 'success', 'mensagem' => 'Manutenção cadastrada com sucesso!'];
    } catch (PDOException $e) {
        return ['status' => 'error', 'mensagem' => 'Erro ao cadastrar manutenção: ' . $e->getMessage()];
    }
}


// Buscar dados do carro
function buscarCarroPorId(PDO $pdo, int $carroId, int $usuarioId): ?array
{
    $sql = "SELECT * FROM carros 
            WHERE id = :carro_id AND usuario_id = :usuario_id 
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':carro_id' => $carroId,
        ':usuario_id' => $usuarioId
    ]);
    $carro = $stmt->fetch(PDO::FETCH_ASSOC);
    return $carro ?: null;
}

// Buscar manutenções por carro
function buscarManutencoesPorCarro(PDO $pdo, int $carroId, int $usuarioId): array
{
    $sql = "SELECT 
                m.id,
                COALESCE(m.data, '') AS data,
                COALESCE(m.km, '') AS km,
                COALESCE(m.valor, '') AS valor,
                COALESCE(m.local, '') AS local,
                COALESCE(m.pago, 0) AS pago,
                COALESCE(m.proxima_manut_data, '') AS proxima_manut_data,
                COALESCE(m.proxima_manut_km, '') AS proxima_manut_km,
                COALESCE(m.descricao, '') AS descricao,
                COALESCE(t.nome, '') AS tipo_nome
            FROM manutencoes_carro m
            LEFT JOIN tipos_manutencao t ON m.tipo_id = t.id
            WHERE m.carro_id = :carro_id 
              AND m.usuario_id = :usuario_id
            ORDER BY m.data DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'carro_id' => $carroId,
        'usuario_id' => $usuarioId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Calcular resumo de custos (último mês e mês atual)
function calcularResumoCustos(PDO $pdo, int $carroId, int $usuarioId): array
{
    // Mês atual
    $sqlAtual = "SELECT SUM(valor) as total 
                 FROM manutencoes_carro 
                 WHERE carro_id = :carro_id 
                   AND usuario_id = :usuario_id
                   AND MONTH(data) = MONTH(CURDATE())
                   AND YEAR(data) = YEAR(CURDATE())";
    $stmt = $pdo->prepare($sqlAtual);
    $stmt->execute([
        ':carro_id' => $carroId,
        ':usuario_id' => $usuarioId
    ]);
    $atual = $stmt->fetchColumn() ?: 0;

    // Último mês
    $sqlUltimo = "SELECT SUM(valor) as total 
                  FROM manutencoes_carro 
                  WHERE carro_id = :carro_id 
                    AND usuario_id = :usuario_id
                    AND MONTH(data) = MONTH(CURDATE() - INTERVAL 1 MONTH)
                    AND YEAR(data) = YEAR(CURDATE() - INTERVAL 1 MONTH)";
    $stmt = $pdo->prepare($sqlUltimo);
    $stmt->execute([
        ':carro_id' => $carroId,
        ':usuario_id' => $usuarioId
    ]);
    $ultimo = $stmt->fetchColumn() ?: 0;

    return [
        'atual' => (float) $atual,
        'ultimo' => (float) $ultimo
    ];
}
