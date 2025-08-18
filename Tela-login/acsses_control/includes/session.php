<?php

function redirecionarSeLogado() {
    if (isset($_SESSION['usuario'])) {
        header('Location:' . BASE_URL . 'pages/index.php');
        exit;
    }
}

// session.php — inicialização centralizada de sessão

// Define um nome único para a sessão (opcional, mas evita conflitos)
session_name('financeiro_sessao_global');

// Garante que o cookie de sessão funcione em todas as pastas
ini_set('session.cookie_path', '/');

// Inicia a sessão apenas se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Função para verificar se o usuário está logado
function verificaUsuarioLogado() {
    if (empty($_SESSION['usuario']) || empty($_SESSION['usuario_id'])) {
        header('Location: ' . BASE_URL . 'acsses_control/pages/login.php');
        exit;
    }
}

