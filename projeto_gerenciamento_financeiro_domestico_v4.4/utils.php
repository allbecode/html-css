<?php
function contribuicao_valida(string $nome): bool {
    // Lista de nomes válidos para cálculo de dízimos/ofertas
    $nomesValidos = [
        'Provisão Salarial', 
        'Cartão Alimentação', 
        'Vale Transporte',
        'Horas extras',
        '13º Salário',
        'Férias',
        'PLR',
        'Dividendos',
        'PIS/PASEP',
        'Rescisão Trabalhista'
    ];

    return in_array($nome, $nomesValidos);
}
