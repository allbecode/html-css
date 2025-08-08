<?php
require_once 'db.php';

function buscarUsuarioPorUsername(string $username) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function atualizarEmail(int $id, string $email): bool {
    $pdo = conectar();
    $stmt = $pdo->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
    return $stmt->execute([$email, $id]);
}

function atualizarNomeCompleto(int $id, string $nome, string $sobrenome, string $data_nasc): bool {
    $pdo = conectar();
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, sobrenome = ?, data_nascimento = ? WHERE id = ?");
    return $stmt->execute([$nome, $sobrenome, $data_nasc, $id]);
}

function atualizarSenha(int $id, string $novaSenha): bool {
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $pdo = conectar();
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    return $stmt->execute([$hash, $id]);
}

function buscarUsuarioEDependente(int $usuarioId): array {
    global $pdo;

    $stmt = $pdo->prepare("SELECT nome AS usuario, dependente FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $usuarioId]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    return [
        'usuario' => $dados['usuario'] ?? 'Usuário',
        'dependente' => $dados['dependente'] ?? null
    ];
}

