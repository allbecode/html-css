<?php
require_once __DIR__ . '/../acsses_control/includes/db.php';
require_once __DIR__ . '/../acsses_control/includes/functions.php';

// NOVAS FUNÇÕES (NOVA MODELAGEM) ---------------------------------------------

// Adicionar novas manutenções
function cadastrarManutencao(PDO $pdo, int $usuarioId, int $carroId, array $dados): array
{
    try {
        // 1. Inserir manutenção
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
            'carro_id'              => $carroId,
            'usuario_id'            => $usuarioId,
            'tipo_id'               => $dados['tipo_id'] ?? null,
            'descricao'             => !empty($dados['descricao']) ? $dados['descricao'] : null,
            'data'                  => $dados['data'] ?? null,
            'km'                    => !empty($dados['km']) ? $dados['km'] : null,
            'valor'                 => !empty($dados['valor']) ? $dados['valor'] : null,
            'forma_pagamento_id'    => $dados['forma_pagamento_id'] ?? null,
            'local'                 => !empty($dados['local']) ? $dados['local'] : null,
            'pago'                  => isset($dados['pago']) ? 1 : 0,
            'proxima_manut_data'    => !empty($dados['proxima_manut_data']) ? $dados['proxima_manut_data'] : null,
            'proxima_manut_km'      => !empty($dados['proxima_manut_km']) ? $dados['proxima_manut_km'] : null,
        ];

        $stmt = $pdo->prepare("
            INSERT INTO manutencoes_carro 
            (carro_id, usuario_id, tipo_id, descricao, data, km, valor, forma_pagamento_id, local, pago, proxima_manut_data, proxima_manut_km) 
            VALUES (:carro_id, :usuario_id, :tipo_id, :descricao, :data, :km, :valor, :forma_pagamento_id, :local, :pago, :proxima_manut_data, :proxima_manut_km)
        ");

        $stmt->execute($dadosNormalizados);

        $manutencaoId = $pdo->lastInsertId();

        // 2. Criar transação vinculada
        // Adicionar manutenção ao registro financeiro

        $ano = (int)date('Y', strtotime($dados['data']));
        $mes = (int)date('n', strtotime($dados['data']));
        $dataRegistro = date('Y-m-d');

        if (!empty($dados['forma_pagamento_id'])) {
            $stmt = $pdo->prepare("SELECT nome FROM forma_pagamento WHERE id = :id");
            $stmt->execute(['id' => $dados['forma_pagamento_id']]);
            $formaPagamentoNome = $stmt->fetchColumn();
        } else {
            $formaPagamentoNome = null;
        }


        $dadosFinanceiros = [
            'usuario_id'        => $usuarioId,
            'nome'              => 'Manutenção do Carro',
            'data_vencimento'   => !empty($dados['data']) ? $dados['data'] : null,
            'valor'             => isset($dados['valor']) && $dados['valor'] !== '' ? (float)$dados['valor'] : null,
            'tipo'              => 'Despesa',
            'forma_pagamento'   => $formaPagamentoNome ?? null,
            'descricao'         => !empty($dados['descricao']) ? $dados['descricao'] : null,
            'ano'               => $ano,
            'mes'               => $mes,
            'pago'              => isset($dados['pago']) ? 1 : 0,
            'data_registro'     => $dataRegistro,
            'base_contribuicao' => 0,
        ];

        $stmt = $pdo->prepare("
            INSERT INTO transacoes (
                usuario_id,
                nome, 
                data_vencimento, 
                valor, 
                tipo, 
                forma_pagamento, 
                descricao, 
                ano, 
                mes, 
                pago, 
                data_registro,
                base_contribuicao
            ) VALUES (
                :usuario_id,
                :nome, 
                :data_vencimento, 
                :valor, 
                :tipo, 
                :forma_pagamento, 
                :descricao, 
                :ano, 
                :mes, 
                :pago, 
                :data_registro,
                :base_contribuicao
            )
        ");
        $stmt->execute($dadosFinanceiros);

        $transacaoId = $pdo->lastInsertId();

        // 3. Atualizar manutenção com ID da transação
        $stmt = $pdo->prepare("UPDATE manutencoes_carro SET transacao_id = :transacao_id WHERE id = :id");
        $stmt->execute([
            'transacao_id' => $transacaoId,
            'id'           => $manutencaoId
        ]);


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
                COALESCE(t.nome, '') AS tipo_nome,
                COALESCE(fp.nome, '') AS forma_pagamento_nome
            FROM manutencoes_carro m
            LEFT JOIN tipos_manutencao t ON m.tipo_id = t.id
            LEFT JOIN forma_pagamento fp ON m.forma_pagamento_id = fp.id
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


// Alerta de manutenções vencidas e a vencer
function alertManutencaoLinha(?string $prox_manut_data, string $tipo): ?string
{
    if (!$prox_manut_data) {
        return null;
    }

    $hoje = new DateTime();
    $proxima = new DateTime($prox_manut_data);

    // Vencida
    if ($proxima < $hoje) {
        $diasVencidos = $hoje->diff($proxima)->days;
        return "<tr class='alerta-manutencao'>
                    <td colspan='6' class='alerta-vencida'>
                        O serviço: {$tipo} encontra-se vencido há {$diasVencidos} dias.
                    </td>
                </tr>";
    }

    // A vencer em até 30 dias
    if ($proxima >= $hoje && $proxima <= (clone $hoje)->modify('+30 days')) {
        $diasRestantes = $hoje->diff($proxima)->days;
        return "<tr class='alerta-manutencao'><td colspan='6' class='alerta-a-vencer'>
                   A manutenção acima irá vencer em {$diasRestantes} dias.
                </td></tr>";
    }

    // Fora das condições
    return null;
}

function classAlertaColuna(?string $prox_manut_data): string
{
    if(!$prox_manut_data){
        return "";
    }

    $hoje = new DateTime();
    $proxima = new DateTime($prox_manut_data);

    if($proxima < $hoje){
        return "alerta-vencida-col"; // classe CSS para vencida
    }

    if ($proxima <= (clone $hoje)->modify('+30 days')) {
        return "alerta-a-vencer-col"; // classe CSS para a vencer
    }

    return "";
}


