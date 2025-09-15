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

// Função para popular o badge-manutenção no header.php
document.addEventListener("DOMContentLoaded", () => {
  fetch("../actions/count_manutencoes.php")
    .then(res => res.json())
    .then(data => {
      const badge = document.getElementById("badge-manutencao");
      const vencidas = data.vencidas ?? 0;

      badge.textContent = vencidas;

      // Se preferir esconder o badge quando for 0:
      badge.style.display = vencidas > 0 ? "inline-block" : "none";
    })
    .catch(err => console.error("Erro ao carregar badge:", err));
});

