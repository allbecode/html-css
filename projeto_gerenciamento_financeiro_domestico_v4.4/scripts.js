function fecharRelatorio() {
    window.close();
}

function mudouTamanho() {
    if (window.innerWidth >= 768) {
        itens.style.display = 'block'
    } else {
        itens.style.display = 'none'
    }
}

function clickMenu() {
    if (itens.style.display == 'block') {
        itens.style.display = "none"
    } else {
        itens.style.display = 'block'
    }
}

function anoAtual() {
    let ano = document.getElementById('ano');
    let anoAtual = new Date().getFullYear();
    ano.innerHTML += `${anoAtual}`;
}

document.addEventListener('DOMContentLoaded', function () {
    const campos = ['valor', 'ano', 'mes', 'nome']; // nomes dos inputs

    campos.forEach(function (nomeCampo) {
        const input = document.querySelector(`input[name="${nomeCampo}"]`);
        if (input) {
            input.addEventListener('focus', function () {
                this.select();
            });

            // A substituição ocorre automaticamente ao digitar com o conteúdo selecionado
        }
    });
});
