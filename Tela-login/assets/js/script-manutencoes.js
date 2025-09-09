// document.querySelectorAll('.btn-manutencoes').forEach(btn => {
//     btn.addEventListener('click', () => {
// const carroId = btn.dataset.carroId;
// document.getElementById('carro_id').value = carroId;

// carregarTipos('tipo_manutencao'); // 🔥 carrega dinamicamente os tipos

// document.getElementById('modalManutencoes').style.display = 'block';

// carregar histórico de manutenções
// fetch('../actions/listar_manutencoes.php?carro_id=' + carroId)
//     .then(r => r.text())
//     .then(html => document.getElementById('listaManutencoes').innerHTML = html);

//     });
// });






// document.querySelectorAll('.btn-manutencoes').forEach(btn => {
//     btn.addEventListener('click', () => {
//         const carroId = btn.dataset.carroId;
//         // document.getElementById('carro_id').value = carroId;

//         // carregarTipos('tipo_manutencao'); // 🔥 carrega dinamicamente os tipos

//         document.getElementById('modalManutencoes').style.display = 'block';

//         // carregar histórico de manutenções
//         fetch('../actions/listar_manutencoes.php?carro_id=' + carroId)
//             .then(r => r.text())
//             .then(html => document.getElementById('listaManutencoes').innerHTML = html);
//     });
// });

// document.querySelector('#formManutencao').addEventListener('submit', function(e){
//   e.preventDefault();
//   let formData = new FormData(this);

//   fetch('../actions/add_manutencao.php', { method:'POST', body: formData })
//     .then(r => r.json())
//     .then(res => {
//       alert(res.mensagem);
//       if(res.status === 'success') {
//         // recarregar lista
//         fetch('../actions/listar_manutencoes.php?carro_id=' + formData.get('carro_id'))
//           .then(r => r.text())
//           .then(html => document.getElementById('listaManutencoes').innerHTML = html);
//         this.reset();
//       }
//     });
// });


// document.querySelector('.close').addEventListener('click', function () {
//     document.getElementById('modalManutencoes').style.display = 'none';
// });

// ----------------------------------------------------

// Delegação de eventos para exclusão (funciona em elementos criados dinamicamente)
// document.getElementById('listaManutencoes').addEventListener('click', function (e) {
//   if (e.target.classList.contains('btn-excluir')) {
//     if (!confirm("Deseja excluir esta manutenção?")) return;

//     const id = e.target.dataset.id;
//     fetch('../actions/delete_manutencao.php', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/x-www-form-urlencoded'
//       },
//       body: 'id=' + id
//     })
//       .then(r => r.text())
//       .then(res => {
//         if (res === "success") {
//           alert("Manutenção excluída com sucesso!");
//           // recarregar lista sem reload da página inteira
//           const carroId = document.getElementById('carro_id').value;
//           fetch('../actions/list_manutencoes.php?carro_id=' + carroId)
//             .then(r => r.text())
//             .then(html => document.getElementById('listaManutencoes').innerHTML = html);
//         } else {
//           alert(res);
//         }
//       });
//   }
// });


document.querySelectorAll(".delete-btn").forEach(btn => {
  btn.addEventListener("click", async () => {
    if (!confirm("Deseja realmente excluir esta manutenção?")) return;

    const id = btn.dataset.id;
    const carroId = btn.dataset.carro;

    const formData = new FormData();
    formData.append("id", id);
    formData.append("carro_id", carroId);

    try {
      const res = await fetch("../actions/delete_manutencao.php", {
        method: "POST",
        body: formData
      });

      const data = await res.json();

      alert(data.mensagem);

      if (data.status === "success") {
        // Remove a linha da tabela sem reload
        const row = btn.closest("tr");
        if (row) row.remove();
      }
    } catch (err) {
      alert("Erro inesperado ao excluir manutenção.");
      console.error(err);
    }
  });
});



// // Abrir modal de edição
// document.getElementById('listaManutencoes').addEventListener('click', function (e) {
//   if (e.target.classList.contains('btn-editar')) {
//     const id = e.target.dataset.id;

//     fetch('../actions/get_manutencao.php?id=' + id)
//       .then(r => r.json())
//       .then(dados => {
//         document.getElementById('editar_id').value = dados.id;
//         document.getElementById('editar_data').value = dados.data;
//         document.getElementById('editar_km').value = dados.km ?? '';
//         document.getElementById('editar_valor').value = dados.valor ?? '';
//         document.getElementById('editar_pago').value = String(dados.pago ?? '0');
//         document.getElementById('editar_proxima_data').value = dados.proxima_manut_data ?? '';
//         document.getElementById('editar_proxima_km').value = dados.proxima_manut_km ?? '';
//         document.getElementById('editar_descricao').value = dados.descricao ?? '';

//         // ⚠️ Aguarde popular o select e pré-selecionar o tipo antes de abrir o modal
//         return carregarTipos('editar_tipo', dados.tipo);
//       })
//       .then(() => {
//         document.getElementById('modalEditarManutencao').style.display = 'block';
//       })
//       .catch(err => console.error(err));
//   }
// });


// // Fechar modal
// document.getElementById('closeModalEditar').onclick = () => {
//     document.getElementById('modalEditarManutencao').style.display = 'none';
// };

// // Submeter edição
// document.getElementById('formEditarManutencao').addEventListener('submit', function (e) {
//     e.preventDefault();

//     fetch('../actions/edit_manutencao.php', {
//         method: 'POST',
//         body: new FormData(this)
//     })
//     .then(r => r.text())
//     .then(res => {
//         if (res === "success") {
//             alert("Manutenção atualizada com sucesso!");
//             document.getElementById('modalEditarManutencao').style.display = 'none';

//             // Recarregar lista
//             const carroId = document.getElementById('carro_id').value;
//             fetch('../actions/listar_manutencoes.php?carro_id=' + carroId)
//                 .then(r => r.text())
//                 .then(html => document.getElementById('listaManutencoes').innerHTML = html);
//         } else {
//             alert("Erro: " + res);
//         }
//     });
// });


// ================================================================================


document.addEventListener("DOMContentLoaded", () => {
  const botaoAbrirModal = document.querySelectorAll(".abrir-modal-manutencao");
  const modal = document.getElementById("modalManutencao");
  const fecharModal = document.getElementById("fecharModalManutencao");

  // if (abrirModal && modal && fecharModal) {
    botaoAbrirModal.forEach(botao => {
      botao.addEventListener("click", (e) => {
        e.preventDefault();
        modal.classList.remove("hidden");
      });
    });

    fecharModal.addEventListener("click", () => {
      modal.classList.add("hidden");
    });

    window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.add("hidden");
      }
    });
  // }
});




// Carregar os tipos de manutenção

// document.getElementById("modalManutencao").addEventListener("click", () => {
//   carregarTipos("tipo"); // Carrega a lista apenas quando abrir o modal
// });

document.addEventListener("DOMContentLoaded", () => {
            // Carregar tipos assim que a página carrega
            carregarTipos("tipo");
        });

function carregarTipos(selectId, valorSelecionado = null) {
  const endpoint = '../actions/tipos_manutencao.php?nocache=' + Date.now();

  return fetch(endpoint)
    .then(res => res.json())
    .then(data => {
      // Força formato array [{id, nome}]
      const tipos = (Array.isArray(data) ? data : [])
        .map(item => {
          if (typeof item === 'object' && item.id && item.nome) {
            return { id: item.id, nome: item.nome };
          } else if (typeof item === 'string') {
            return { id: item, nome: item }; // fallback, se vier só string
          }
          return null;
        })
        .filter(Boolean)
        .sort((a, b) => a.nome.localeCompare(b.nome, 'pt-BR', { sensitivity: 'base' }));

      const select = document.getElementById(selectId);
      select.innerHTML = '<option value="">Selecione o tipo...</option>';

      let selecionou = false;

      tipos.forEach(tipo => {
        // const opt = new Option(tipo.nome, tipo.nome);
        const opt = new Option(tipo.nome, tipo.id);
        if (valorSelecionado && String(tipo.id) === String(valorSelecionado)) {
          opt.selected = true;
          selecionou = true;
        }
        select.add(opt);
      });

      // Se o valor salvo não existir mais na lista, adiciona-o e seleciona
      if (valorSelecionado && !selecionou) {
        const opt = new Option("⚠ Tipo removido", valorSelecionado, true, true);
        select.add(opt, 1);
      }
    })
    .catch(err => {
      console.error('Erro ao carregar tipos de manutenção:', err);
    });
}


// <!-- Script Menu Mobile -->

//  document.getElementById('menuToggle').addEventListener('click', function() {
//             const menu = document.getElementById('mobileMenu');
//             menu.classList.toggle('hidden');
//         });


document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.getElementById("menuToggle");
  const mobileMenu = document.getElementById("mobileMenu");

  if (!menuToggle || !mobileMenu) return;

  menuToggle.addEventListener("click", () => {
    // alterna a classe hidden no menu
    mobileMenu.classList.toggle("hidden");

    // alterna o ícone do botão
    if (mobileMenu.classList.contains("hidden")) {
      menuToggle.textContent = "☰"; // hamburger
    } else {
      menuToggle.textContent = "✖"; // X
    }
  });
});



// Modo Edição
const modal = document.getElementById("modalEdicao");
const closeBtn = modal.querySelector(".close");
// const fecharModal = document.getElementById("fecharModalEdicao");

document.querySelectorAll(".edit-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("edit-id").value = btn.dataset.id;
    document.getElementById("edit-carro").value = btn.dataset.carro;
    document.getElementById("edit-data").value = btn.dataset.data;
    document.getElementById("edit-descricao").value = btn.dataset.descricao;
    document.getElementById("edit-km").value = btn.dataset.km;
    document.getElementById("edit-valor").value = btn.dataset.valor;
    document.getElementById("edit-local").value = btn.dataset.local;
    
    // Garante que se nao houverem dados, os inputs fiquem limpos
    // document.getElementById("edit-prox-data").value = btn.dataset.proxData || "";
    // document.getElementById("edit-prox-km").value = btn.dataset.proxKm || "";
    document.getElementById("edit-prox-data").value = btn.dataset.proxData && btn.dataset.proxData !== "null" ? btn.dataset.proxData : "";
    document.getElementById("edit-prox-km").value = btn.dataset.proxKm && btn.dataset.proxKm !== "null" ? btn.dataset.proxKm : "";  

    document.getElementById("edit-pago").checked = btn.dataset.pago === "1";

    // carregar tipos dinâmicos e já selecionar o atual
    carregarTipos("edit-tipo", btn.dataset.tipoId);

    // abre modal
    modal.classList.remove("hidden");
  });

  window.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.add("hidden");
      }
    });
});

closeBtn.addEventListener("click", () => {
  modal.classList.add("hidden");
});


// Fluxo AJAX para SALVAR manutenções direto do modal, exibindo um alerta de sucesso/erro sem recarregar a página.
const formManutencao = document.getElementById("form-manutencao");
const alerta = document.getElementById("alerta");

formManutencao.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(formManutencao);

  try {
    const res = await fetch(formManutencao.action, {
      method: "POST",
      body: formData,
    });

    const data = await res.json();

    alerta.classList.remove("hidden");
    alerta.textContent = data.mensagem;
    alerta.style.color = data.status === "success" ? "green" : "red";

    if (data.status === "success") {
      // Fecha modal e recarrega a lista sem reload total
      setTimeout(() => {
        document.getElementById("modalManutencao").classList.add("hidden");
        location.reload(); // ou podemos atualizar só a tabela via AJAX futuramente
      }, 1200);
    }
  } catch (err) {
    alerta.classList.remove("hidden");
    alerta.textContent = "Erro inesperado ao salvar manutenção.";
    alerta.style.color = "red";
    console.error(err);
  }
});


// Fluxo AJAX para EDITAR manutenções direto do modal, exibindo um alerta de sucesso/erro sem recarregar a página.
const formEditar = document.getElementById("form-editar-manutencao");
const alertaEdicao = document.getElementById("alerta-edicao");

formEditar.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(formEditar);

  try {
    const res = await fetch(formEditar.action, {
      method: "POST",
      body: formData,
    });

    const data = await res.json();

    alertaEdicao.classList.remove("hidden");
    alertaEdicao.textContent = data.mensagem;
    alertaEdicao.style.color = data.status === "success" ? "green" : "red";

    if (data.status === "success") {
      setTimeout(() => {
        document.getElementById("modalEdicao").classList.add("hidden");
        location.reload(); // recarregar lista de manutenções
      }, 1200);
    }
  } catch (err) {
    alertaEdicao.classList.remove("hidden");
    alertaEdicao.textContent = "Erro inesperado ao atualizar manutenção.";
    alertaEdicao.style.color = "red";
    console.error(err);
  }
});



// Visualizar manutenção
// Toggle de detalhes por LINHA
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.toggle-details');
  if (!btn) return;

  const tr = btn.closest('tr');
  if (!tr) return;

  // pega a primeira célula de detalhes da linha para ler o estado atual
  const firstDetailCell = tr.querySelector('td.detalhes');
  if (!firstDetailCell) return;

  const willShow = firstDetailCell.classList.contains('hidden'); // se está escondido agora, vamos mostrar

  // aplica o mesmo estado a TODAS as células de detalhes da mesma linha
  tr.querySelectorAll('td.detalhes').forEach(td => {
    td.classList.toggle('hidden', !willShow); // remove 'hidden' se willShow=true; adiciona se willShow=false
  });

  // acessibilidade + ícone
  btn.setAttribute('aria-expanded', willShow ? 'true' : 'false');
  btn.title = willShow ? 'Ocultar detalhes' : 'Visualizar detalhes';
  btn.textContent = willShow ? '🙈' : '👁️';
});


// JavaScript (Toggle do Menu)
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const toggle = document.getElementById("toggleSidebar");

  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("expanded");
  });
});





