<?php

function verificarDuplicidadeCarro(PDO $pdo, int $usuarioId, string $placa, ?string $renavan = null, ?int $carroId = null): array
{
    $placa = strtoupper(trim($placa));
    $renavan = $renavan !== null ? trim($renavan) : null;

    try {
        // 1️⃣ Verifica duplicidade da placa para o próprio usuário
        $sql = "SELECT COUNT(*) FROM carros WHERE usuario_id = :usuario_id AND placa = :placa";
        $params = ['usuario_id' => $usuarioId, 'placa' => $placa];

        if ($carroId) {
            $sql .= " AND id != :carro_id";
            $params['carro_id'] = $carroId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetchColumn() > 0) {
            return [
                'status' => 'error',
                'mensagem' => 'Essa placa já foi cadastrada para este usuário.'
            ];
        }

        // 2️⃣ Verifica duplicidade do RENAVAN para o próprio usuário, se informado
        if (!empty($renavan)) {
            $sqlRenavan = "SELECT COUNT(*) FROM carros WHERE usuario_id = :usuario_id AND renavan = :renavan";
            $paramsRenavan = ['usuario_id' => $usuarioId, 'renavan' => $renavan];

            if ($carroId) {
                $sqlRenavan .= " AND id != :carro_id";
                $paramsRenavan['carro_id'] = $carroId;
            }

            $stmt = $pdo->prepare($sqlRenavan);
            $stmt->execute($paramsRenavan);
            if ($stmt->fetchColumn() > 0) {
                return [
                    'status' => 'error',
                    'mensagem' => 'Este RENAVAN já está cadastrado.'
                ];
            }
        }

        // 3️⃣ Verifica se o carro já existe em OUTROS usuários
        $sqlOutros = "SELECT usuario_id FROM carros 
                  WHERE (placa = :placa OR renavan = :renavan) 
                    AND usuario_id != :usuario_id";
        $params = [
            'placa' => $placa,
            'renavan' => $renavan,
            'usuario_id' => $usuarioId
        ];

        if ($carroId) {
            $sqlOutros .= " AND id != :carro_id";
            $params['carro_id'] = $carroId;
        }
        $stmt = $pdo->prepare($sqlOutros);
        $stmt->execute($params);

        if ($stmt->fetch()) {
            return [
                'status' => 'warning',
                'mensagem' => 'Atenção: este veículo já foi registrado por outro usuário, mas você também pode cadastrá-lo normalmente.'
            ];
        }
        // ✅ Sem conflitos
        return ['status' => 'ok', 'mensagem' => ''];
    } catch (Exception $e) {
        return [
            'status' => 'error', 
            'mensagem' => 'Erro ao verificar duplicidade: ' . $e->getMessage()
        ];
    }
}

/**
 * Define mensagem de alerta (sucesso, erro ou aviso)
 */
function setAlert(string $mensagem, string $tipo = "info"): void
{
    $_SESSION['mensagem_carro'] = $mensagem;
    $_SESSION['tipo_mensagem_carro'] = $tipo;
}

/**
 * Exibe e limpa o alerta (alert JavaScript)
 */
function showAlert(): void
{
    if (isset($_SESSION['mensagem_carro'])) {
        $mensagem = addslashes($_SESSION['mensagem_carro']);
        echo "<script>alert('{$mensagem}');</script>";
        unset($_SESSION['mensagem_carro'], $_SESSION['tipo_mensagem_carro']);
    }
}
