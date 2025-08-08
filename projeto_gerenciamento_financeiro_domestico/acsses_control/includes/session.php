<?php
function redirecionarSeLogado() {
    if (isset($_SESSION['usuario'])) {
        header("Location: painel.php");
        exit;
    }
}
