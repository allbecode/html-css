<?php
// Arquivo de funções reutilizáveis

// Formata valores em R$ 1.234,56
function formatarValor($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Converte data ISO (yyyy-mm-dd) para BR (dd/mm/yyyy)
function formatarDataBr($dataIso) {
    if (!$dataIso || !str_contains($dataIso, '-')) return $dataIso;
    list($ano, $mes, $dia) = explode('-', $dataIso);
    return "$dia/$mes/$ano";
}

// Converte data BR para ISO
function formatarDataIso($dataBr) {
    if (!$dataBr || !str_contains($dataBr, '/')) return $dataBr;
    list($dia, $mes, $ano) = explode('/', $dataBr);
    return "$ano-$mes-$dia";
}

// Calculo de Dízimo/Ofertas
function calcularDizimoOferta($totalReceitas) {
    return $totalReceitas * 0.10;
}

// Verifica se há receitas
function temReceitas($totalReceitas){
    if($totalReceitas > 0){
        return true;
    } else {
        return false;
    }
}
