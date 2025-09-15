
// Manipulação das transações com AJAX
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.button-icon.delete').forEach(botao => {
        // Excluir transações
        botao.addEventListener('click', function () {
            const id = this.dataset.id;
            const linha = this.closest('tr');

            if (confirm('Tem certeza que deseja excluir esta transação?')) {
                fetch('../actions/delete_transaction.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}`
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            linha.remove();
                        } else {
                            alert('Erro ao excluir: ' + (data.mensagem || 'Erro desconhecido.'));
                        }
                    })
                    .catch(() => {
                        alert('Erro na comunicação com o servidor.');
                    });
            }
        });
    });

    const linhas = document.querySelectorAll('.transacao-linha');
    const mensagem = document.getElementById('mensagem-edicao');
    let clicado = false;

    linhas.forEach((linha, index) => {
        linha.addEventListener('click', () => {
            document.querySelectorAll('.transacao-linha').forEach(l => {
                l.classList.remove('selecionada');
                l.style.backgroundColor = '';
                l.style.border = '';
            });

            linha.classList.add('selecionada');

            if (!clicado && mensagem) {
                mensagem.style.display = 'block';
                setTimeout(() => mensagem.style.display = 'none', 2000);
                clicado = true;
            } else {
                clicado = false;
            }

            linhaSelecionadaIndex = index;
        });

        linha.addEventListener('dblclick', () => ativarModoEdicao(linha));
    });

    // Editar transações
    async function ativarModoEdicao(linha) {

        // 🔒 Bloqueia se a linha for vinculada a manutenção
        if (linha.dataset.vinculo === "manutencao") {
            alert("Esta transação está vinculada a uma manutenção e só pode ser editada pelo módulo de Manutenções.");
            return;
        }

        const campos = linha.querySelectorAll('[data-field]');
        const botaoSalvar = linha.querySelector('.button-icon.salvar');
        const botaoExcluir = linha.querySelector('.button-icon.delete');

        if (botaoSalvar) {
            botaoSalvar.classList.remove('hidden');
            botaoSalvar.classList.add('visible');
        }

        if (botaoExcluir) {
            botaoExcluir.classList.remove('visible');
            botaoExcluir.classList.add('hidden');
        }

        for (const campo of campos) {
            const nomeCampo = campo.dataset.field;
            const valorAtual = campo.innerText.trim();

            switch (nomeCampo) {
                case 'data_vencimento':
                    const dataIso = valorAtual.includes('/') ? valorAtual.split('/').reverse().join('-') : valorAtual;
                    campo.innerHTML = `<input type="date" value="${dataIso}">`;
                    break;

                case 'valor':
                    const numero = valorAtual.replace('R$', '').replace('.', '').replace(',', '.').trim();
                    campo.innerHTML = `<input type="number" step="0.01" value="${numero}" style="width: 100px;">`;
                    break;

                case 'tipo':
                    campo.innerHTML = `
                    <select>
                        <option value="receita" ${valorAtual === 'Receita' ? 'selected' : ''}>Receita</option>
                        <option value="despesa" ${valorAtual === 'Despesa' ? 'selected' : ''}>Despesa</option>
                    </select>`;
                    break;

                case 'forma_pagamento':
                    campo.innerHTML = `
                    <select>
                        <option value="Boleto Bancário" ${valorAtual === 'Boleto Bancário' ? 'selected' : ''}>Boleto Bancário</option>
                        <option value="Cartão de Crédito" ${valorAtual === 'Cartão de Credito' ? 'selected' : ''}>Cartão de Crédito</option>
                        <option value="Cheque" ${valorAtual === 'Cheque' ? 'selected' : ''}>Cheque</option>
                        <option value="Crédito em Conta" ${valorAtual === 'Crédito em Conta' ? 'selected' : ''}>Crédito em Conta</option>
                        <option value="Débito em Conta" ${valorAtual === 'Débito em Conta' ? 'selected' : ''}>Débito em Conta</option>
                        <option value="Débito Automático" ${valorAtual === 'Débito Automático' ? 'selected' : ''}>Débito Automático</option>
                        <option value="Espécie" ${valorAtual === 'Espécie' ? 'selected' : ''}>Espécie</option>
                        <option value="PIX" ${valorAtual === 'PIX' ? 'selected' : ''}>PIX</option>
                    </select>`;
                    break;

                case 'pago':
                    campo.innerHTML = `
                    <select>
                        <option value="1" ${valorAtual === '✔' ? 'selected' : ''}>✔</option>
                        <option value="0" ${valorAtual === '✖' ? 'selected' : ''}>✖</option>
                    </select>`;
                    break;

                case 'nome':
                    campo.innerHTML = `<select disabled><option>Carregando...</option></select>`;
                    const tipoSelect = linha.querySelector('[data-field="tipo"] select');
                    const tipoSelecionado = tipoSelect ? tipoSelect.value : linha.querySelector('[data-field="tipo"]')?.innerText.toLowerCase();

                    if (tipoSelecionado) {
                        try {
                            const response = await fetch(`../assets/json/${tipoSelecionado}.json?v=${Date.now()}`);
                            const opcoes = await response.json();
                            const selectNome = document.createElement('select');

                            const encontrado = opcoes.some(op => op.nome === valorAtual);
                            if (!encontrado) {
                                const fallback = document.createElement('option');
                                fallback.value = valorAtual;
                                fallback.textContent = valorAtual + ' (não listado)';
                                fallback.selected = true;
                                selectNome.appendChild(fallback);
                            }

                            opcoes.forEach(opcao => {
                                const selected = opcao.nome === valorAtual ? 'selected' : '';
                                const option = `<option value="${opcao.value}" ${selected}>${opcao.nome}</option>`;
                                selectNome.innerHTML += option;
                            });

                            campo.innerHTML = '';
                            campo.appendChild(selectNome);
                        } catch (error) {
                            console.error('Erro ao carregar nomes:', error);
                            campo.innerHTML = '<select><option>Erro ao carregar</option></select>';
                        }
                    } else {
                        campo.innerHTML = '<select><option>Selecione o tipo primeiro</option></select>';
                    }
                    break;
                default:
                    campo.contentEditable = true;
                    campo.style.backgroundColor = '#fff';
            }
        }

        // Define o que acontece ao clicar no botão "Salvar"
        document.querySelectorAll('.button-icon.salvar').forEach(botaoSalvar => {
            botaoSalvar.onclick = () => {
                const linha = botaoSalvar.closest('tr');
                const id = linha.dataset.id;
                const campos = linha.querySelectorAll('[data-field]');
                const botaoExcluir = linha.querySelector('.button-icon.delete');
                const dados = { id };

                campos.forEach(campo => {
                    const nomeCampo = campo.dataset.field;

                    if (nomeCampo === 'data_vencimento') {
                        const input = campo.querySelector('input[type="date"]');
                        dados[nomeCampo] = input?.value || '';
                        campo.innerText = formatarDataBr(dados[nomeCampo]);

                    } else if (nomeCampo === 'valor') {
                        const input = campo.querySelector('input[type="number"]');
                        dados[nomeCampo] = parseFloat(input?.value || 0).toFixed(2);
                        campo.innerText = formatarValor(dados[nomeCampo]);
                    } else if (['tipo', 'forma_pagamento', 'pago', 'nome'].includes(nomeCampo)) {
                        const select = campo.querySelector('select');
                        dados[nomeCampo] = select?.value || '';
                        campo.innerText = select?.options[select.selectedIndex]?.text || '';

                    } else {
                        dados[nomeCampo] = campo.innerText.trim();
                        campo.contentEditable = false;
                        campo.style.backgroundColor = '';
                    }

                    if (botaoSalvar && botaoExcluir) {
                        botaoSalvar.classList.remove('visible');
                        botaoSalvar.classList.add('hidden');
                        botaoExcluir.classList.remove('hidden');
                        botaoExcluir.classList.add('visible');
                    }
                });

                if (!dados.nome) {
                    alert("O campo 'nome' não pode estar vazio.");
                    return;
                }

                fetch('../actions/edit_transaction.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dados)
                })
                    .then(res => res.json())
                    .then(json => {
                        if (json.status === 'ok') {
                            botaoSalvar.style.display = 'none';
                            linha.classList.remove('selecionada');
                            linha.style.backgroundColor = '';
                        } else {
                            alert('Erro ao atualizar: ' + (json.mensagem || 'Erro desconhecido.'));
                        }
                    })
                    .catch(() => alert('Erro na comunicação com o servidor.'));
            };
        });
    }

    aplicarSelecaoAoFocar();

    // Tecla ESC para cancelar edição
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const emEdicao = document.querySelector('.transacao-linha input, .transacao-linha select, .transacao-linha[contenteditable="true"]');
            if (emEdicao) location.reload();
        }
    });

    // Navegação com teclas ↑ ↓
    let linhaSelecionadaIndex = -1;
    const linhasArray = Array.from(document.querySelectorAll('.transacao-linha'));

    function atualizarSelecao(index) {
        linhasArray.forEach((linha, i) => {
            if (i === index) {
                linha.classList.add('selecionada');
                linha.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                linha.classList.remove('selecionada');
                linha.style.backgroundColor = '';
                linha.style.border = '';
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown' && linhaSelecionadaIndex < linhasArray.length - 1) {
            linhaSelecionadaIndex++;
            atualizarSelecao(linhaSelecionadaIndex);
        } else if (e.key === 'ArrowUp' && linhaSelecionadaIndex > 0) {
            linhaSelecionadaIndex--;
            atualizarSelecao(linhaSelecionadaIndex);
        }
    });
    focarPrimeiroCampo();
});
