<?php
require_once 'db.php';

function iniciarSessao(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
}

function protegerPagina(): void {
    iniciarSessao();
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../acsses_control/pages/login.php");
        exit;
    }
}

function usuarioLogadoTipo() {
    return $_SESSION['tipo'] ?? 'usuario';
}

function verificarPermissaoAdmin(): void {
    if ($_SESSION['tipo'] !== 'admin') {
        echo "Acesso restrito!";
        exit;
    }
}

/**
 * Retorna o ID do usuário logado na sessão atual
 *
 * @return int|null
 */
function getUsuarioId()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
}

// function verificaUsuarioLogado() {
//     if (!isset($_SESSION)) {
//         session_start();
//     }

//     if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
//         header('Location: ../acsses_control/pages/login.php');
//         exit;
//     }
// }

// function verificaUsuarioLogado() {
//     if (session_status() === PHP_SESSION_NONE) {
//         session_start();
//     }

//     if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
//         header('Location: ../acsses_control/pages/login.php');
//         exit;
//     }
// }

// function verificaUsuarioLogado() {
//     if (session_status() === PHP_SESSION_NONE) {
//         session_start();
//     }

//     $usuarioId = $_SESSION['usuario_id'] 
//         ?? ($_SESSION['usuario']['id'] ?? null);

//     if (empty($usuarioId)) {
//         header('Location: ../acsses_control/pages/login.php');
//         exit;
//     }
// }

/*
function verificaUsuarioLogado() {
    // Garantir sessão ativa
    if (session_status() === PHP_SESSION_NONE) {
        // Garantir que o cookie funcione em todo o domínio
        if (!headers_sent()) {
            ini_set('session.cookie_path', '/');
        }
        session_start();
    }

    // Se não houver usuário logado, redireciona para login
    if (empty($_SESSION['usuario_id'])) {
        header('Location: ../acsses_control/pages/login.php');
        exit;
    }
}
*/




