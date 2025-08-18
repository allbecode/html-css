document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('select[name="mes"], input[name="ano"], select[name="tipo_contribuicao"]');

    selects.forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('form-geral').submit();
        });
    });

    // Aplicar comportamento de foco e seleção automática
    aplicarSelecaoAoFocar();
    focarPrimeiroCampo();
});


document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-geral');

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