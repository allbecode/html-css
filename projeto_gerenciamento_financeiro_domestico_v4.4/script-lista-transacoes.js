// Coloca o foco na caixa de seleção "tipo" do formulário
window.onload = document.getElementById('tipo').focus();


// AJAX - Exclui a linha/transação sem atualizar a tela
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.button.delete').forEach(botao => {
        botao.addEventListener('click', function () {
            const id = this.dataset.id;
            const linha = this.closest('tr');

            if (confirm('Tem certeza que deseja excluir esta transação?')) {
                fetch('delete_transaction.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}`
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            linha.remove(); // Remove a linha da tabela
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
});


// Edita a linha/transação sem atualizar a tela
document.addEventListener('DOMContentLoaded', function () {
    const linhas = document.querySelectorAll('.transacao-linha');
    const mensagem = document.getElementById('mensagem-edicao');
    let clicado = false;

    linhas.forEach(linha => {
        linha.addEventListener('click', () => {
            // Desmarca outras linhas
            document.querySelectorAll('.transacao-linha').forEach(l => {
                l.classList.remove('selecionada');
                l.style.backgroundColor = '';
                l.style.border = '';
            });

            // Marca a linha clicada
            linha.classList.add('selecionada');
            linha.style.backgroundColor = '#D9E6FC';
            linha.style.border = '5px solid #063042';

            // Mensagem flutuante (uma vez)
            if (!clicado) {
                mensagem.style.display = 'block';
                setTimeout(() => mensagem.style.display = 'none', 5000);
                clicado = true;
            } else {
                clicado = false
            }
        });

        linha.addEventListener('dblclick', () => {
            const id = linha.dataset.id;
            const campos = linha.querySelectorAll('[data-field]');
            const botaoSalvar = linha.querySelector('.button.salvar');

            // Oculta o botão "Excluir"
            const botaoExcluir = linha.querySelector('.button.delete');
            if (botaoExcluir) {

                // Mostrar "Salvar"
                botaoSalvar.classList.remove('hidden');
                botaoSalvar.classList.add('visible');

                // Ocultar "Excluir"
                botaoExcluir.classList.remove('visible');
                botaoExcluir.classList.add('hidden');


            }


            campos.forEach(campo => {
                const nomeCampo = campo.dataset.field;
                const valorAtual = campo.innerText.trim();

                switch (nomeCampo) {
                    case 'data_vencimento':
                        const dataIso = valorAtual.includes('/') ?
                            valorAtual.split('/').reverse().join('-') :
                            valorAtual;
                        campo.innerHTML = `<input type="date" value="${dataIso}" style="width: 140px;">`;
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
                        <option value= "1" ${valorAtual === '✔' ? 'selected' : ''} > ✔ </option>
                        <option value= "0" ${valorAtual === '✖' ? 'selected' : ''} > ✖ </option>
                    </select>`;
                        break;

                    default:
                        campo.contentEditable = true;
                        campo.style.backgroundColor = '#fffbe6';
                }
            });

            // Habilitar ESC para cancelar
            linha.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    location.reload(); // recarrega a página inteira
                }
            });

            // Botão salvar
            if (botaoSalvar) {
                botaoSalvar.style.display = 'inline-block';

                botaoSalvar.onclick = () => {
                    const dados = {
                        id
                    };

                    campos.forEach(campo => {
                        const nomeCampo = campo.dataset.field;

                        if (nomeCampo === 'data_vencimento') {
                            const input = campo.querySelector('input[type="date"]');
                            dados[nomeCampo] = input?.value || input?.defaultValue || originalData[nome];
                            campo.innerText = formatarDataBr(dados[nomeCampo]);

                        } else if (nomeCampo === 'valor') {
                            const input = campo.querySelector('input[type="number"]');
                            dados[nomeCampo] = parseFloat(input?.value || 0).toFixed(2);
                            campo.innerText = `R$ ${parseFloat(dados[nomeCampo]).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;

                        } else if (nomeCampo === 'tipo' || nomeCampo === 'forma_pagamento') {
                            const select = campo.querySelector('select');
                            dados[nomeCampo] = select.value;
                            campo.innerText = select.options[select.selectedIndex].text;

                        } else if (nomeCampo === 'pago') {
                            const select = campo.querySelector('select');
                            dados[nomeCampo] = select.value;
                            campo.innerText = select.options[select.selectedIndex].text;

                        } else {
                            dados[nomeCampo] = campo.innerText.trim();
                            campo.contentEditable = false;
                            campo.style.backgroundColor = '';
                        }

                        if (botaoExcluir) {
                            botaoSalvar.classList.remove('visible');
                            botaoSalvar.classList.add('hidden');

                            botaoExcluir.classList.remove('hidden');
                            botaoExcluir.classList.add('visible');

                        }

                    });

                    fetch('edit_transaction.php', {
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
            }

        });

    });

    // Utilitário: formata data ISO para dd/mm/yyyy
    function formatarDataBr(dataIso) {
        if (!dataIso || !dataIso.includes('-')) return dataIso;
        const [ano, mes, dia] = dataIso.split('-');
        return `${dia}/${mes}/${ano}`;
    }
});


// Movimetar a seleção da linha com as setas
document.addEventListener('DOMContentLoaded', function () {
    const linhas = Array.from(document.querySelectorAll('.transacao-linha'));
    let linhaSelecionadaIndex = -1;

    // Reaplica o estilo de seleção visual
    function atualizarSelecao(index) {
        linhas.forEach((linha, i) => {
            if (i === index) {
                linha.classList.add('selecionada');
                linha.style.border = '5px solid #063042';
                linha.style.backgroundColor = '#D9E6FC';
                linha.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                linha.classList.remove('selecionada');
                linha.style.backgroundColor = '';
                linha.style.border = '';
            }
        });
    }

    // function ativarModoEdicao(linha) {
    //     linha.dispatchEvent(new MouseEvent('dblclick', { bubbles: true }));
    // }

    function ativarModoEdicao(linha) {
        linha.dispatchEvent(new MouseEvent('dblclick', { bubbles: true }));

        // Aguarda a renderização dos inputs para aplicar o foco
        setTimeout(() => {
            const inputOuSelect = linha.querySelector('input, select, [contenteditable="true"]');
            if (inputOuSelect) {
                inputOuSelect.focus();

                // Se for um campo de texto ou número, também seleciona o conteúdo
                if (inputOuSelect.select) {
                    inputOuSelect.select();
                }
            }
        }, 100); // pequeno delay para garantir que o HTML foi alterado
    }


    // Movimentação com setas ↑ ↓
    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown') {
            if (linhaSelecionadaIndex < linhas.length - 1) {
                linhaSelecionadaIndex++;
                atualizarSelecao(linhaSelecionadaIndex);
            }
        } else if (e.key === 'ArrowUp') {
            if (linhaSelecionadaIndex > 0) {
                linhaSelecionadaIndex--;
                atualizarSelecao(linhaSelecionadaIndex);
            }
        } else if (e.key === 'Enter') {
            if (linhaSelecionadaIndex >= 0) {
                ativarModoEdicao(linhas[linhaSelecionadaIndex]);
            }
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            // Verifica se há algum input ou select visível (indicador de modo edição)
            const emEdicao = document.querySelector('.transacao-linha input, .transacao-linha select, .transacao-linha[contenteditable="true"]');

            if (emEdicao) {
                location.reload(); // ESC cancela edição com reload total
            }
        }
    });


    // Clique do mouse atualiza índice para sincronizar com as teclas
    linhas.forEach((linha, index) => {
        linha.addEventListener('click', () => {
            linhaSelecionadaIndex = index;
            atualizarSelecao(index);
        });
    });
});
