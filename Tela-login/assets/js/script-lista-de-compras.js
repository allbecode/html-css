document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-produto');
    const select = document.getElementById('produto');
    const qtd = document.getElementById('quantidade');
    const tabela = document.querySelector('#tabela-compras tbody');
    const botaoLimpar = document.getElementById('btn-limpar-lista');
    const mensagemVazia = document.getElementById('mensagem-vazia');
    const botoesAcoes = document.getElementById('botoes-acoes');

    // Função para atualizar o estado da interface
    function atualizarVisibilidadeInterface() {
        const temItens = tabela.querySelectorAll('tr').length > 0;
        mensagemVazia.style.display = temItens ? 'none' : 'block';
        botoesAcoes.style.display = temItens ? 'block' : 'none';
    }

    const itensSalvos = JSON.parse(sessionStorage.getItem('lista')) || [];
    itensSalvos.forEach(([produto, quantidade]) => adicionarNaTabela(produto, quantidade));
    atualizarVisibilidadeInterface();

    form.addEventListener('submit', e => {
        e.preventDefault();
        const produto = select.value;
        const quantidade = parseInt(qtd.value);
        if (produto && quantidade > 0) {
            if (!estaNaLista(produto)) {
                adicionarNaTabela(produto, quantidade);
                salvarLista();
                focarPrimeiroCampo()
            } else {
                alert(`Este item já foi adicionado à lista.\nPor favor selecione outro produto.`);
                focarPrimeiroCampo();
            }

        }
        aplicarSelecaoAoFocar()
    });

    // Verificando se o item selecionado já está na lista
    function estaNaLista(itemText) {
        const linhas = tabela.querySelectorAll('tr');
        for (let linha of linhas) {
            const produtoNaLinha = linha.querySelector('td');
            if (produtoNaLinha) {
                const produtoTexto = produtoNaLinha.textContent.trim().toLowerCase();
                if (produtoTexto === itemText.trim().toLowerCase()) {
                    return true;
                }
            }
        }
        return false;
    }

    // Adicionando itens à tabela.
    function adicionarNaTabela(produto, quantidade) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
                    <td>${produto}</td>
                    <td>${quantidade} unidade${quantidade > 1 ? 's' : ''}</td>
                    <td class="no-print">
                        <button title="Editar Produto" class="button-icon" id="btn-editar">✏️</button>
                        <button title="Salvar Edição" class="button-icon" id="btn-salvar" style="display:none;">💾</button>
                        <button title="Excluir produto" class="button-icon" id="btn-excluir">🗑️</button>
                    </td>
                `;
        tr.querySelector('#btn-excluir').addEventListener('click', () => {
            if (confirm('Tem certeza que deseja apagar este produto?')) {
                tr.remove();
                salvarLista();
                atualizarVisibilidadeInterface();
            }
        });
        tabela.appendChild(tr);
        atualizarVisibilidadeInterface();

        const btnEditar = tr.querySelector('#btn-editar');
        const btnSalvar = tr.querySelector('#btn-salvar');
        const btnExcluir = tr.querySelector('#btn-excluir');

        // Editando itens da tabela
        btnEditar.addEventListener('click', () => {
            const tdProduto = tr.children[0];
            const tdQtd = tr.children[1];

            const produtoAtual = tdProduto.textContent;
            const qtdAtual = parseInt(tdQtd.textContent);

            tdProduto.innerHTML = `<input type="text" value="${produtoAtual}" style="width:100%;">`;
            tdQtd.innerHTML = `<input type="number" min="1" value="${qtdAtual}" style="width:60px;">`;

            btnEditar.style.display = 'none';
            btnExcluir.style.display = 'none';
            btnSalvar.style.display = 'inline-block';

            // Foco no campo de texto
            const inputProduto = tdProduto.querySelector('input');
            inputProduto.focus();
            inputProduto.select(); // opcional: seleciona todo o texto
        });

        // Tecla ESC para cancelar edição
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const emEdicao = document.querySelector('tr input, tr select');
                if (emEdicao) location.reload();
            }
        });

        btnSalvar.addEventListener('click', () => {
            const tdProduto = tr.children[0];
            const tdQtd = tr.children[1];

            const novoProduto = tdProduto.querySelector('input').value.trim();
            const novaQtd = parseInt(tdQtd.querySelector('input').value);

            if (!novoProduto || isNaN(novaQtd) || novaQtd < 1) {
                alert('Preencha os campos corretamente.');
                return;
            }

            tdProduto.textContent = novoProduto;
            tdQtd.textContent = `${novaQtd} unidade${novaQtd > 1 ? 's' : ''}`;

            btnEditar.style.display = 'inline-block';
            btnExcluir.style.display = 'inline-block'
            btnSalvar.style.display = 'none';

            salvarLista();
            atualizarVisibilidadeInterface();
        });

    }

    // Salvando os itens da tabela
    function salvarLista() {
        const linhas = Array.from(tabela.querySelectorAll('tr'));
        const dados = linhas.map(tr => {
            const cols = tr.querySelectorAll('td');
            return [cols[0].innerText, cols[1].innerText.replace(/[^0-9]/g, '')];
        });
        sessionStorage.setItem('lista', JSON.stringify(dados));
    }

    // Limpar a lista de compras
    botaoLimpar.addEventListener('click', () => {
        if (confirm('Tem certeza que deseja apagar toda a lista?')) {
            tabela.innerHTML = ''; // Limpa todas as linhas da tabela
            sessionStorage.removeItem('lista'); // Remove da sessão
            atualizarVisibilidadeInterface();
        }

    });

    focarPrimeiroCampo()

}); // Fim - DOMContentLoaded +===================================

// Carregando a lista suspensa de produtos
async function carregarProdutos() {
    const select = document.getElementById('produto');
    try {
        const response = await fetch('../assets/json/produtos.json');
        const categorias = await response.json();

        select.innerHTML = '<option value="">Selecione um produto</option>';

        categorias.forEach(grupo => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = grupo.categoria;

            grupo.itens.forEach(prod => {
                const option = document.createElement('option');
                option.value = prod.nome;
                option.textContent = prod.nome;
                optgroup.appendChild(option);
            });

            select.appendChild(optgroup);
        });
    } catch (erro) {
        console.error('Erro ao carregar produtos:', erro);
        select.innerHTML = '<option value="">Erro ao carregar produtos</option>';
    }
}

carregarProdutos();
