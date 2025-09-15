document.addEventListener('DOMContentLoaded', () => {
  fetch('../actions/count_manutencoes_por_carro.php', {cache: 'no-store'})
    .then(res => {
      if (!res.ok) throw new Error('Resposta inválida');
      return res.json();
    })
    .then(data => {
      // Espera-se formato: { "12": 2, "13": 0, ... } (carro_id => qtd)
      Object.entries(data).forEach(([carroId, qtd]) => {
        const badge = document.getElementById(`badge-carro-${carroId}`);
        if (!badge) return;
        const n = Number(qtd) || 0;
        if (n > 0) {
          badge.textContent = n;
          badge.style.display = 'inline-block';
          badge.title = `${n} manutenção(ões) vencida(s)`;
        } else {
          badge.style.display = 'none';
        }
      });
    })
    .catch(err => {
      console.error('Erro ao carregar badges por carro:', err);
    });
});

