// Formata uma data ISO (aaaa-mm-dd) para BR (dd/mm/aaaa)
function formatarDataBr(dataIso) {
    if (!dataIso || !dataIso.includes('-')) return dataIso;
    const [ano, mes, dia] = dataIso.split('-');
    return `${dia}/${mes}/${ano}`;
}

// Converte data BR (dd/mm/aaaa) para ISO (aaaa-mm-dd)
function converterDataParaIso(dataBr) {
    if (!dataBr || !dataBr.includes('/')) return dataBr;
    const [dia, mes, ano] = dataBr.split('/');
    return `${ano}-${mes}-${dia}`;
}

// Formata um valor float para moeda brasileira
function formatarValor(valor) {
    if (isNaN(valor)) return 'R$ 0,00';
    return 'R$ ' + parseFloat(valor).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Seleciona o conteúdo ao focar no campo
function aplicarSelecaoAoFocar(campos = ['valor', 'ano', 'mes', 'nome']) {
    campos.forEach(function (nomeCampo) {
        const input = document.querySelector(`input[name="${nomeCampo}"]`);
        if (input) {
            input.addEventListener('focus', function () {
                this.select();
            });
        }
    });
}

// Foca e seleciona o primeiro campo do formulário (usado também em AJAX)
function focarPrimeiroCampo(formSelector = 'form', delay = 50) {
    setTimeout(() => {
        const form = document.querySelector(formSelector);
        if (!form) return;

        const primeiroCampo = form.querySelector('input, select, textarea');
        if (primeiroCampo && typeof primeiroCampo.focus === 'function') {
            primeiroCampo.focus();

            if (
                ['text', 'number', 'email', 'search', 'tel', 'url'].includes(primeiroCampo.type) ||
                primeiroCampo.tagName === 'TEXTAREA'
            ) {
                primeiroCampo.select();
            }
        }
    }, delay);
}

// Exibe uma mensagem temporária no elemento alvo
function exibirMensagemTemporaria(elemento, mensagem, tempo = 4000) {
    if (!elemento) return;
    elemento.textContent = mensagem;
    elemento.style.display = 'block';
    setTimeout(() => {
        elemento.textContent = '';
        elemento.style.display = 'none';
    }, tempo);
}

// Exibe as transações realizadas no dia
function carregarTransacoesDoDia() {
    fetch('../actions/listar_transacoes_dia.php')
        .then(res => res.text())
        .then(html => {
            const container = document.getElementById('transacoes-do-dia');
            if (container) {
                container.innerHTML = html;
            }
        });
}

// imprime listas
function imprimirLista() {
    window.print();
}

// Fechar guias abertas, desde que tenham sido abertas automaticamente
function fecharRelatorio() {
    window.close();
}


