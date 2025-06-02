document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-marcar').forEach(botao => {
        botao.addEventListener('click', () => {
            const id = botao.dataset.id;
            const valor = parseFloat(botao.dataset.valor.replace(',', '.'));

            if (confirm('Deseja realmente marcar esta despesa como paga?')) {
                fetch('marcar_pago.php', {
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
            const corpo = document.querySelector(`#tbody-${tipo}`);
            if (!corpo) return;

            const linhas = corpo.querySelectorAll('tr');
            let total = 0;

            linhas.forEach(tr => {
                const valorTd = tr.querySelector('td:nth-child(4)');
                if (valorTd) {
                    const texto = valorTd.textContent.replace('R$', '').trim().replace('.', '').replace(',', '.');
                    const valor = parseFloat(texto);
                    if (!isNaN(valor)) total += valor;
                }
            });

            const totalSpan = document.querySelector(`#total-${tipo}`);
            if (totalSpan) {
                totalSpan.textContent = 'R$ ' + total.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });
    }
});