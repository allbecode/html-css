document.getElementById('tipo_contribuicao').addEventListener('change', function () {
    const tipo = this.value;
    document.getElementById('resumo_dizimo').style.display = (tipo === 'dizimo') ? 'block' : 'none';
    document.getElementById('resumo_oferta').style.display = (tipo === 'oferta') ? 'block' : 'none';
});

// Exibir automaticamente se já foi submetido
window.onload = function () {
    const tipo = document.getElementById('tipo_contribuicao').value;
    if (tipo === 'dizimo') document.getElementById('resumo_dizimo').style.display = 'block';
    if (tipo === 'oferta') document.getElementById('resumo_oferta').style.display = 'block';
};
