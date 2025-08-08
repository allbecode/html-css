<?php
require_once 'db.php';

function iniciarSessao(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
}

function protegerPagina(): void {
    iniciarSessao();
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../peges/login.php");
        exit;
    }
}

// function verificaUsuarioLogado() {
//     if (!isset($_SESSION)) {
//         session_start();
//     }

//     if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
//         header('Location: ../pages/login.php');
//         exit;
//     }
// }



function usuarioLogadoTipo() {
    return $_SESSION['tipo'] ?? 'usuario';
}

function verificarPermissaoAdmin(): void {
    if ($_SESSION['tipo'] !== 'admin') {
        echo "Acesso restrito!";
        exit;
    }
}
