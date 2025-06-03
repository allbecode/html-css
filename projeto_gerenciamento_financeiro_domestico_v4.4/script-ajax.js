// // AJAX - Formulário Salvar Conteúdo do formulário + Atualizar tela 

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const origem = form.dataset.origem;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok') {
                        if (origem === 'transacao') {
                            // Apenas limpa o formulário ou mostra feedback, sem reload
                            form.reset();
                            alert('Transação adicionada com sucesso!');
                            carregarTransacoesDoDia(); // atualiza lista
                            // Chama o foco no novo campo após a reinicialização
                            focarPrimeiroCampo('#form-transacao');
                        } else if (origem === 'contribuicao') {
                            // Recarrega a página se for transação
                            location.reload();
                            alert("Contribuição registrada com sucesso!");
                        }
                    } else {
                        alert("Erro ao salvar os dados.");
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert("Erro na comunicação com o servidor.");
                });
        });
    });
    // carrega ao iniciar também
    carregarTransacoesDoDia();
});


function carregarTransacoesDoDia() {
    fetch('listar_transacoes_dia.php')
        .then(res => res.text())
        .then(html => {
            const container = document.getElementById('transacoes-do-dia');
            if (container) {
                container.innerHTML = html;
            }
        });
}
