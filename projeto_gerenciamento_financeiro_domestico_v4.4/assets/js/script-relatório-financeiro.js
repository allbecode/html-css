document.addEventListener('DOMContentLoaded', function () {
    const botaoImprimir = document.getElementById('btn-imprimir-relatorio');

    if (botaoImprimir) {
        botaoImprimir.addEventListener('click', () => {
            window.print();
        });
    }
});