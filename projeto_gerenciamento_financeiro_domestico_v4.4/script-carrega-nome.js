document.getElementById("tipo").addEventListener("change", async function() {
    const arquivoJSON = this.value; // Obtém o arquivo correspondente
    
    const selectNome = document.getElementById("nome");
    selectNome.innerHTML = '<option value="">Carregando...</option>'; // Exibe carregamento temporário

    if (arquivoJSON) {
        try {
            const response = await fetch(arquivoJSON + '.json');
            const dados = await response.json();
            
            selectNome.innerHTML = '<option value="">Selecione...</option>'; // Reseta opções
            
            dados.forEach(opcao => {
                let elemento = document.createElement("option");
                elemento.value = opcao.value;
                elemento.textContent = opcao.nome;
                selectNome.appendChild(elemento);
            });
        } catch (error) {
            console.error("Erro ao carregar as opções:", error);
            selectNome.innerHTML = '<option value="">Erro ao carregar</option>';
        }
    } else {
        selectNome.innerHTML = '<option value="">Selecione um tipo primeiro</option>';
    }
});