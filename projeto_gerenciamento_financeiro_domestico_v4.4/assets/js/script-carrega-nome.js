document.getElementById("tipo").addEventListener("change", async function () {
    const arquivoJSON = this.value;
    const selectNome = document.getElementById("nome");

    selectNome.innerHTML = '<option value="">Carregando...</option>';

    if (arquivoJSON) {
        try {
            // Força o navegador a buscar uma versão atualizada do JSON
            const response = await fetch(`../assets/json/${arquivoJSON}.json?v=${Date.now()}`);
            const dados = await response.json();

            selectNome.innerHTML = '<option value="">Selecione...</option>';

            dados.forEach(opcao => {
                const elemento = document.createElement("option");
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
