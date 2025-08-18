<?php
function validarLogin($usuario, $senha) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$usuario]);
    $usuario_bd = $stmt->fetch();

    if ($usuario_bd && verificarSenha($senha, $usuario_bd['senha'])) {
        // Salva último login (atualiza depois de verificar se é o primeiro)
        $primeiro_acesso = is_null($usuario_bd['ultimo_login']);

        // Atualiza a data do último login
        $stmt = $pdo->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?");
        $stmt->execute([$usuario_bd['id']]);

        return [
            'sucesso' => true,
            'dados' => [
                'username' => $usuario_bd['username'],
                'tipo' => $usuario_bd['tipo'],
                'id' => $usuario_bd['id'],
                'nome' => $usuario_bd['nome'],
                'primeiro_acesso' => $primeiro_acesso 
            ]
        ];
    }

    return ['sucesso' => false, 'mensagem' => 'Usuário ou senha inválidos'];
}


