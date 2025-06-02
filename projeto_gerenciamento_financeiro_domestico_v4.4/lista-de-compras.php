<?php
// lista-de-compras.php — versão com tabela + exclusão + botão de impressão
include 'header.php';
$anoAtual = date('Y');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-lista-transacoes.css">
    <link rel="stylesheet" href="style_relatorio_contribuicao.css">
    <link rel="stylesheet" href="style-lista-de-compras.css">


    <title>Lista de Compras</title>
</head>

<body>
    <main>
            <h2 class="no-print">Lista de Compras</h2>
            <form id="form-produto" class="form-filtro no-print">
                <label for="produto">Produto:</label>
                <select id="produto" required>
                    <option value="">Selecione um produto...</option>
                    <!-- A lista suspensa de de produtos será carregada aqui. -->
                </select>
                <label for="quantidade">Qtd:</label>
                <input type="number" id="quantidade" min="1" value="1" required>
                <button type="submit">Selecionar</button>
            </form>
            <div class="container">
                <table id="tabela-compras">
                    <thead>
                        <tr>
                            <th colspan="3" class="print">Lista de Compras</th>
                        </tr>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th class="no-print">Ação</th>
                        </tr>
                    </thead>
                    <tbody id="lista-produtos-selecionados">
                        <!-- Os produtos selacionados serão exibidos aqui -->
                    </tbody>
                    <tfoot>
                        <td></td>
                    </tfoot>
                </table>
                <p id="mensagem-vazia" style="color: gray;">Nenhum item adicionado à lista.</p>
                <div id="botoes-acoes" class="no-print" style="display: none;">
                    <button title="Imprimir Lista" class="btn-imprimir no-print" onclick="imprimirLista()">🖨️</button>
                    <button title="Limpar Lista" class="btn-limpar no-print" id="btn-limpar-lista">🧹</button>
                </div>
            </div>
            
                </main>
      
    <?php include 'footer.php'; ?>

    <script>
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
                        // select.selectedIndex = 0;
                        // qtd.value = 1;
                        select.focus();
                    } else {
                        alert(`Este item já foi adicionado à lista.\nPor favor selecione outro produto.`);
                        select.focus();
                    }

                }
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
                        <button title="Editar Produto" class="btn-editar">✏️</button>
                        <button title="Salvar Edição" class="btn-salvar" style="display:none;">💾</button>
                        <button title="Excluir produto" class="btn-excluir">🗑️</button>
                    </td>
                `;
                tr.querySelector('.btn-excluir').addEventListener('click', () => {
                    if (confirm('Tem certeza que deseja apagar este produto?')) {
                    tr.remove();
                    salvarLista();
                    atualizarVisibilidadeInterface();
                    }
                });
                tabela.appendChild(tr);
                atualizarVisibilidadeInterface();

                const btnEditar = tr.querySelector('.btn-editar');
                const btnSalvar = tr.querySelector('.btn-salvar');

                btnEditar.addEventListener('click', () => {
                    const tdProduto = tr.children[0];
                    const tdQtd = tr.children[1];

                    const produtoAtual = tdProduto.textContent;
                    const qtdAtual = parseInt(tdQtd.textContent);

                    tdProduto.innerHTML = `<input type="text" value="${produtoAtual}" style="width:100%;">`;
                    tdQtd.innerHTML = `<input type="number" min="1" value="${qtdAtual}" style="width:60px;">`;

                    btnEditar.style.display = 'none';
                    btnSalvar.style.display = 'inline-block';

                    // Foco no campo de texto
                    const inputProduto = tdProduto.querySelector('input');
                    inputProduto.focus();
                    inputProduto.select(); // opcional: seleciona todo o texto
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

            select.focus();

        });

        // Fim - DOMContentLoaded +===================================

        // Carregando a lista suspensa de produtos
        async function carregarProdutos() {
            const select = document.getElementById('produto');
            try {
                const response = await fetch('produtos.json');
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

        // Imprimindo a lista de compras
        function imprimirLista() {
            window.print()
            select.focus()
        }

        // window.onload = document.getElementById('produto').focus()
    </script>
</body>

</html>