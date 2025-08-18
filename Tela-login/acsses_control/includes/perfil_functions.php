<?php
require_once 'functions.php';

function obterDadosUsuario($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function atualizarEmail($pdo, $usuario_id, $novo_email) {
    if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        return "Email inválido.";
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
    $stmt->execute([$novo_email, $usuario_id]);
    return true;
}

function atualizarSenha($pdo, $username, $senha_atual, $nova_senha) {
    $usuario = obterDadosUsuario($pdo, $username);
    if (!verificarSenha($senha_atual, $usuario['senha'])) {
        return "Senha atual incorreta!";
    }

    $hash = hashSenha($nova_senha);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE username = ?");
    $stmt->execute([$hash, $username]);
    return true;
}

function atualizarInformacoesPessoais($pdo, $usuario_id, $nome, $sobrenome, $data_nascimento) {
    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, sobrenome = ?, data_nascimento = ? WHERE id = ?");
    $stmt->execute([$nome, $sobrenome, $data_nascimento, $usuario_id]);
    return true;
}



