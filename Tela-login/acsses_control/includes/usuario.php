<?php
require_once 'db.php';

function buscarUsuarioPorUsername(string $username)
{
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function atualizarEmail($id, $email)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
        $stmt->execute([$email, $id]);
        return true;
    } catch (PDOException $e) {
        // Verifica se é erro de chave duplicada
        if ($e->getCode() == 23000) {
            return "<p style='color:red;'>Este e-mail já está em uso. Escolha outro.</p>";
        } else {
            return "<p style='color:red;'>Erro inesperado ao atualizar o e-mail.</p>";
        }
    }
}


function atualizarNomeCompleto(int $id, string $nome, string $sobrenome, string $data_nasc): bool
{
    $pdo = conectar();
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, sobrenome = ?, data_nascimento = ? WHERE id = ?");
    return $stmt->execute([$nome, $sobrenome, $data_nasc, $id]);
}

function atualizarSenha(int $id, string $novaSenha): bool
{
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $pdo = conectar();
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    return $stmt->execute([$hash, $id]);
}

function buscarUsuarioEDependente(int $usuarioId): array
{
    global $pdo;

    // Nome do usuário
    $stmtUser = $pdo->prepare("SELECT nome, sobrenome FROM usuarios WHERE id = :id");
    $stmtUser->execute([':id' => $usuarioId]);
    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

    // Dependentes
    $stmtDep = $pdo->prepare("SELECT nome FROM dependentes WHERE usuario_id = :id");
    $stmtDep->execute([':id' => $usuarioId]);
    $dependentes = $stmtDep->fetchAll(PDO::FETCH_COLUMN);

    if ($usuario) {
        return [
            'usuario' => $usuario['nome'] . ' ' . $usuario['sobrenome'] ?? 'Usuário',
            'dependentes' => $dependentes ?: [] // Array vazio se não houver
        ];
    }else{
        return [
            'usuario' => $usuario['nome'] ?? 'Usuário',
            'dependentes' => $dependentes ?: [] // Array vazio se não houver
        ];
    }
    // return [
    //     'usuario' => $usuario['nome'] ?? 'Usuário',
    //     'dependentes' => $dependentes ?: [] // Array vazio se não houver
    // ];
}

function saudacao($nome = '')
{
    date_default_timezone_set('America/Sao_Paulo');
    $hora = date('H');
    if ($hora >= 6 && $hora <= 11)
        return 'Bom dia' . (empty($nome) ? '' : ', ' . $nome);
    else if ($hora > 11 && $hora <= 17)
        return 'Boa tarde' . (empty($nome) ? '' : ', ' . $nome);
    else
        return 'Boa noite' . (empty($nome) ? '' : ', ' . $nome);
}
