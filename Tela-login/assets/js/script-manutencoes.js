
document.querySelectorAll('.btn-manutencoes').forEach(btn => {
    btn.addEventListener('click', () => {
        const carroId = btn.dataset.carroId;
        document.getElementById('carro_id').value = carroId;
        
        carregarTipos('tipo_manutencao'); // 🔥 carrega dinamicamente os tipos
        
        document.getElementById('modalManutencoes').style.display = 'block';

        // carregar histórico de manutenções
        fetch('../actions/listar_manutencoes.php?carro_id=' + carroId)
            .then(r => r.text())
            .then(html => document.getElementById('listaManutencoes').innerHTML = html);
    });
});

document.querySelector('#formManutencao').addEventListener('submit', function(e){
  e.preventDefault();
  let formData = new FormData(this);

  fetch('../actions/add_manutencao.php', { method:'POST', body: formData })
    .then(r => r.json())
    .then(res => {
      alert(res.mensagem);
      if(res.status === 'success') {
        // recarregar lista
        fetch('../actions/listar_manutencoes.php?carro_id=' + formData.get('carro_id'))
          .then(r => r.text())
          .then(html => document.getElementById('listaManutencoes').innerHTML = html);
        this.reset();
      }
    });
});


document.querySelector('.close').addEventListener('click', function () {
    document.getElementById('modalManutencoes').style.display = 'none';
});

// ----------------------------------------------------

// Delegação de eventos para exclusão (funciona em elementos criados dinamicamente)
document.getElementById('listaManutencoes').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-excluir')) {
        if (!confirm("Deseja excluir esta manutenção?")) return;

        const id = e.target.dataset.id;
        fetch('../actions/delete_manutencao.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + id
        })
            .then(r => r.text())
            .then(res => {
                if (res === "success") {
                    alert("Manutenção excluída com sucesso!");
                    // recarregar lista sem reload da página inteira
                    const carroId = document.getElementById('carro_id').value;
                    fetch('../actions/listar_manutencoes.php?carro_id=' + carroId)
                        .then(r => r.text())
                        .then(html => document.getElementById('listaManutencoes').innerHTML = html);
                } else {
                    alert(res);
                }
            });
    }
});


// Abrir modal de edição
document.getElementById('listaManutencoes').addEventListener('click', function (e) {
  if (e.target.classList.contains('btn-editar')) {
    const id = e.target.dataset.id;

    fetch('../actions/get_manutencao.php?id=' + id)
      .then(r => r.json())
      .then(dados => {
        document.getElementById('editar_id').value = dados.id;
        document.getElementById('editar_data').value = dados.data;
        document.getElementById('editar_km').value = dados.km ?? '';
        document.getElementById('editar_valor').value = dados.valor ?? '';
        document.getElementById('editar_pago').value = String(dados.pago ?? '0');
        document.getElementById('editar_proxima_data').value = dados.proxima_manut_data ?? '';
        document.getElementById('editar_proxima_km').value = dados.proxima_manut_km ?? '';
        document.getElementById('editar_descricao').value = dados.descricao ?? '';

        // ⚠️ Aguarde popular o select e pré-selecionar o tipo antes de abrir o modal
        return carregarTipos('editar_tipo', dados.tipo);
      })
      .then(() => {
        document.getElementById('modalEditarManutencao').style.display = 'block';
      })
      .catch(err => console.error(err));
  }
});


// Fechar modal
document.getElementById('closeModalEditar').onclick = () => {
    document.getElementById('modalEditarManutencao').style.display = 'none';
};

// Submeter edição
document.getElementById('formEditarManutencao').addEventListener('submit', function (e) {
    e.preventDefault();

    fetch('../actions/edit_manutencao.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(r => r.text())
    .then(res => {
        if (res === "success") {
            alert("Manutenção atualizada com sucesso!");
            document.getElementById('modalEditarManutencao').style.display = 'none';

            // Recarregar lista
            const carroId = document.getElementById('carro_id').value;
            fetch('../actions/listar_manutencoes.php?carro_id=' + carroId)
                .then(r => r.text())
                .then(html => document.getElementById('listaManutencoes').innerHTML = html);
        } else {
            alert("Erro: " + res);
        }
    });
});

// Carregar os tipos de manutenção
function carregarTipos(selectId, valorSelecionado = null) {
  const endpoint = '../actions/tipos_manutencao.php?nocache=' + Date.now();

  // helper para comparação sem acento/case
  const norm = (s) =>
    (s ?? '')
      .toString()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '')
      .toLowerCase()
      .trim();

  return fetch(endpoint)
    .then(res => res.json())
    .then(data => {
      // aceita tanto ["Troca de Óleo", ...] quanto [{id:1, nome:"Troca de Óleo"}, ...]
      const tipos = (Array.isArray(data) ? data : [])
        .map(item => (typeof item === 'string' ? item : (item?.nome ?? '')))
        .filter(Boolean)
        .sort((a, b) => a.localeCompare(b, 'pt-BR', { sensitivity: 'base' }));

      const select = document.getElementById(selectId);
      select.innerHTML = '<option value="">Selecione o tipo...</option>';

      let selecionou = false;
      const alvo = norm(valorSelecionado);

      tipos.forEach(nome => {
        const opt = new Option(nome, nome);
        if (valorSelecionado && norm(nome) === alvo) {
          opt.selected = true;
          selecionou = true;
        }
        select.add(opt);
      });

      // Se o valor salvo não existir mais na lista, adiciona-o e seleciona
      if (valorSelecionado && !selecionou) {
        const opt = new Option(valorSelecionado, valorSelecionado, true, true);
        // insere após o placeholder
        select.add(opt, 1);
      }
    })
    .catch(err => {
      console.error('Erro ao carregar tipos de manutenção:', err);
      // fallback: se vier um valor salvo, ao menos preenche para não travar o required
      if (valorSelecionado) {
        const select = document.getElementById(selectId);
        select.innerHTML = '<option value="">Selecione o tipo...</option>';
        const opt = new Option(valorSelecionado, valorSelecionado, true, true);
        select.add(opt, 1);
      }
    });
}



