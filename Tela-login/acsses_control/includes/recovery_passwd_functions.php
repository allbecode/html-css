<?php 

// date_default_timezone_set('America/Sao_Paulo');

function recuperarSenha($email) {
    global $pdo;

    $email = trim($email ?? '');
    if (!$email) {
        return ['tipo' => 'error', 'texto' => 'Informe seu e-mail.'];
    }

    // Verifica se existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        return ['tipo' => 'error', 'texto' => 'E-mail não encontrado.'];
    }

    // Gera token e salva
    $token = bin2hex(random_bytes(16));
    $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacao = :token, token_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = :id");
    $stmt->execute([':token' => $token, ':id' => $usuario['id']]);

    // Aqui poderia enviar o e-mail
    // Exemplo de link: http://seusite.com/pages/redefinir_senha.php?token=$token
    // Mas por enquanto só retorna o token (pra teste)
    return ['tipo' => 'success', 'texto' => "Token gerado: $token"];
}


function validarToken($pdo, $token) {
    $stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacao = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        return ['valido' => false, 'mensagem' => 'Token inválido.', 'usuario' => null];
    }

    if (strtotime($usuario['token_expira']) <= time()) {
        return ['valido' => false, 'mensagem' => 'Token expirado. Solicite uma nova recuperação.', 'usuario' => null];
    }

    return ['valido' => true, 'mensagem' => '', 'usuario' => $usuario];
}


function redefinirSenha($pdo, $usuarioId, $novaSenha) {
    $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ?, token_recuperacao = NULL, token_expira = NULL WHERE id = ?");
    return $stmt->execute([$hash, $usuarioId]);
}

