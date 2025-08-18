<?php
require_once '../includes/db_connection.php';
require_once '../includes/functions.php';
$pageClass = 'sem-menu';

if (!isset($_GET['mes']) || !isset($_GET['ano']) || !isset($_GET['valor_dizimo'])) {
    die("Dados insuficientes para gerar o relatório.");
}

$mes = $_GET['mes'];
$ano = $_GET['ano'];
$nome = $_GET['nome'];
$valor = $_GET['valor_dizimo'];
$descricao = $_GET['descricao'];
?>