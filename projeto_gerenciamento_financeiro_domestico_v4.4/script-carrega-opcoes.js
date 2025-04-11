// Função para carregar os dados do JSON e preencher o select
async function carregarOpcoesFormaPagamento() {
    try {
        const response = await fetch('forma_pagamento.json'); // Carregar JSON
        const dados = await response.json(); // Converter para objeto
        
        const select = document.getElementById("forma_pagamento");

        dados.forEach(opcao => {
            let elemento = document.createElement("option");
            elemento.value = opcao.id;
            elemento.textContent = opcao.nome;
            select.appendChild(elemento);
        });
    } catch (error) {
        console.error("Erro ao carregar as opções:", error);
    }
}

// Chama a função quando a página carrega
document.addEventListener("DOMContentLoaded", carregarOpcoesFormaPagamento);

