<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../acsses_control/includes/db.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

verificaUsuarioLogado();

$usuarioId = $_SESSION['usuario_id'] ?? 0;
$dados = $_POST;

if (!isset($dados['id'], $dados['carro_id'])) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Parâmetros inválidos.']);
    exit;
}

$manutencaoId = (int)$dados['id'];
$carroId = (int)$dados['carro_id'];

try {
    // 🔎 Buscar transacao_id vinculado
    $stmt = $pdo->prepare("SELECT transacao_id FROM manutencoes_carro WHERE id = :id AND carro_id = :carro_id AND usuario_id = :usuario_id");
    $stmt->execute([
        'id'         => $manutencaoId,
        'carro_id'   => $carroId,
        'usuario_id' => $usuarioId
    ]);
    $manutencao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$manutencao) {
        echo json_encode(['status' => 'error', 'mensagem' => 'Manutenção não encontrada.']);
        exit;
    }

    // 🔄 Atualizar manutenção
    $stmt = $pdo->prepare("
        UPDATE manutencoes_carro
        SET data = :data,
            tipo_id = :tipo_id,
            descricao = :descricao,
            km = :km,
            valor = :valor,
            forma_pagamento_id = :forma_pagamento_id,
            local = :local,
            pago = :pago,
            proxima_manut_data = :proxima_manut_data,
            proxima_manut_km = :proxima_manut_km
        WHERE id = :id AND carro_id = :carro_id AND usuario_id = :usuario_id
    ");

    $dadosNormalizados = [
        'id'                    => $manutencaoId,
        'carro_id'              => $carroId,
        'usuario_id'            => $usuarioId,
        'tipo_id'               => $dados['tipo_id'] ?? null,
        'data'                  => $dados['data'] ?? null,
        'forma_pagamento_id'    => $dados['forma_pagamento_id'] ?? null,
        'descricao'             => !empty($dados['descricao']) ? $dados['descricao'] : null,
        'km'                    => !empty($dados['km']) ? $dados['km'] : null,
        'valor'                 => !empty($dados['valor']) ? $dados['valor'] : null,
        'local'                 => !empty($dados['local']) ? $dados['local'] : null,
        'pago'                  => isset($dados['pago']) ? 1 : 0,
        'proxima_manut_data'    => !empty($dados['proxima_manut_data']) ? $dados['proxima_manut_data'] : null,
        'proxima_manut_km'      => !empty($dados['proxima_manut_km']) ? $dados['proxima_manut_km'] : null,
    ];

    $stmt->execute($dadosNormalizados);

    // 🔄 Se houver transação vinculada, atualizar também
    if (!empty($manutencao['transacao_id'])) {
        $ano = (int)date('Y', strtotime($dados['data']));
        $mes = (int)date('n', strtotime($dados['data']));

        if (!empty($dados['forma_pagamento_id'])) {
            $stmt = $pdo->prepare("SELECT nome FROM forma_pagamento WHERE id = :id");
            $stmt->execute(['id' => $dados['forma_pagamento_id']]);
            $formaPagamentoNome = $stmt->fetchColumn();
        } else {
            $formaPagamentoNome = null;
        }

        $stmt = $pdo->prepare("
            UPDATE transacoes
            SET nome = :nome,
                data_vencimento = :data_vencimento,
                valor = :valor,
                tipo = :tipo,
                forma_pagamento = :forma_pagamento,
                descricao = :descricao,
                ano = :ano,
                mes = :mes,
                pago = :pago
            WHERE id = :id AND usuario_id = :usuario_id
        ");
        $stmt->execute([
            'nome'            => 'Manutenção do Carro',
            'data_vencimento' => $dados['data'],
            'valor'           => $dados['valor'] ?? 0,
            'tipo'            => 'Despesa',
            'forma_pagamento' => $formaPagamentoNome ?? null,
            'descricao'       => $dados['descricao'] ?? null,
            'ano'             => $ano,
            'mes'             => $mes,
            'pago'            => isset($dados['pago']) ? 1 : 0,
            'id'              => $manutencao['transacao_id'],
            'usuario_id'      => $usuarioId
        ]);
    }

    echo json_encode(['status' => 'success', 'mensagem' => 'Manutenção e transação atualizadas com sucesso!']);
} catch (Throwable $e) {
    echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao atualizar manutenção: ' . $e->getMessage()]);
}
