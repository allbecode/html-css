<?php 

function registrarUsuario($dados) {
    global $pdo; // conexão definida no seu db.php

    $nome = trim($dados['nome'] ?? '');
    $email = trim($dados['email'] ?? '');
    $senha = trim($dados['senha'] ?? '');
    $confirmar_senha = trim($dados['confirmar_senha'] ?? '');

    if (!$nome || !$email || !$senha || !$confirmar_senha) {
        return ['tipo' => 'error', 'texto' => 'Preencha todos os campos.'];
    }

    if ($senha !== $confirmar_senha) {
        return ['tipo' => 'error', 'texto' => 'As senhas não coincidem.'];
    }

    // Verifica se já existe usuário
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = :nome OR email = :email");
    $stmt->execute([':nome' => $nome, ':email' => $email]);
    if ($stmt->fetch()) {
        return ['tipo' => 'error', 'texto' => 'Nome de usuário ou e-mail já cadastrado.'];
    }

    // Cria o hash da senha
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere no banco master
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, email, senha, tipo_usuario) VALUES (:nome, :email, :senha, 'usuario')");
    $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $hash]);

    if ($stmt->rowCount()) {
        return ['tipo' => 'success', 'texto' => 'Usuário registrado com sucesso!'];
    } else {
        return ['tipo' => 'error', 'texto' => 'Erro ao registrar usuário.'];
    }
}


?>