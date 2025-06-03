document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('select[name="mes"], select[name="ano"], select[name="tipo_contribuicao"]');

    selects.forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('form-contribuicao').submit();
        });
    });
});

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

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-contribuicao');

    // Verifica se a URL já tem a flag ?autocarregado=1
    const urlParams = new URLSearchParams(window.location.search);
    if (form && !urlParams.has('autocarregado')) {
        // Adiciona a flag à URL antes de enviar
        const action = new URL(form.action);
        action.searchParams.set('autocarregado', '1');
        form.action = action.toString();
        form.submit();
    }
});