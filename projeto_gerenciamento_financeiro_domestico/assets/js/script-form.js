document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-mes-ano');
    const chave = location.pathname + '-autoEnvio';
    const enviado = sessionStorage.getItem(chave);

    if (form && !enviado) {
        sessionStorage.setItem(chave, 'true');
        form.submit();
    }

    // Submete o formulário automaticamente ao alterar mês ou ano
    const selects = form.querySelectorAll('select, input');
    selects.forEach(el => {
        el.addEventListener('change', () => {
            form.submit();
        });
    });

    window.addEventListener('beforeunload', () => {
        sessionStorage.removeItem(chave);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form-mes-ano');

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
