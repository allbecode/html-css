// Ao mudar o tamanho da tela os itens do menu serão apresentados ou ocultados
function mudouTamanho() {
    if (window.innerWidth >= 768) {
        itens.style.display = 'block'
    } else {
        itens.style.display = 'none'
    }
}

// Função para abrir e fechar o menu 
function clickMenu() {
    if (itens.style.display == 'block') {
        itens.style.display = "none"
    } else {
        itens.style.display = 'block'
    }
}

