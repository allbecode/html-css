document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-marcar').forEach(botao => {
        botao.addEventListener('click', () => {
            const id = botao.dataset.id;
            const valor = parseFloat(botao.dataset.valor.replace(',', '.'));

            if (confirm('Deseja realmente marcar esta despesa como paga?')) {
                fetch('../actions/marcar_pago.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                })
                    .then(async res => {
                        try {
                            const data = await res.json();
                            if (data.status === 'ok') {
                                const linha = botao.closest('tr');
                                linha.remove();
                                atualizarTotais();
                            } else {
                                alert('Erro ao marcar como paga: ' + (data.mensagem || 'Erro desconhecido.'));
                            }
                        } catch (e) {
                            alert('Erro ao interpretar a resposta do servidor.');
                        }
                    })
                    .catch(() => alert('Erro na comunicação com o servidor.'));
            }
        });
    });

    function atualizarTotais() {
        ['vencidas', 'hoje'].forEach(tipo => {
            const corpo = document.getElementById(`tbody-${tipo}`);
            if (!corpo) return;

            const linhas = corpo.querySelectorAll('tr');
            let total = 0;

            // Verifica e remove linhas ocultas de aviso se estiverem visíveis
            const msgVazia = document.getElementById(`msg-sem-${tipo}`);
            if (msgVazia) msgVazia.style.display = 'none';

            linhas.forEach(tr => {
                const valorTd = tr.querySelector('td:nth-child(4)');
                if (valorTd) {
                    const texto = valorTd.textContent.replace('R$', '').replace(/\./g, '').replace(',', '.');
                    const valor = parseFloat(texto);
                    if (!isNaN(valor)) {
                        total += valor;
                    }
                }
            });

            const spanTotal = document.getElementById(`total-${tipo}`);
            if (spanTotal) {
                spanTotal.textContent = 'R$ ' + total.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Se não houver linhas visíveis, exibe a mensagem
            if (linhas.length === 0 && msgVazia) {
                corpo.appendChild(msgVazia); // garante que está dentro do tbody
                msgVazia.style.display = 'table-row';
            }
        });
    }
});