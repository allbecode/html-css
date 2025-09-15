
// mostrar/ocultar automaticamente o formulário para adição de novos carros
document.addEventListener("DOMContentLoaded", () => {
  const btnNovo = document.getElementById("btn-novo-carro");
  const formCarro = document.getElementById("form-carro");

  btnNovo.addEventListener("click", () => {
    formCarro.classList.toggle("show");

    // Trocar texto do botão
    if (formCarro.classList.contains("show")) {
      // btnNovo.textContent = "✖"; // quando aberto
      btnNovo.textContent = "x"; // quando aberto
    } else {
      btnNovo.textContent = "+"; // quando fechado
    }
  });

  // Após enviar, esconder suavemente e restaurar texto
  formCarro.addEventListener("submit", () => {
    setTimeout(() => {
      formCarro.classList.remove("show");
      formCarro.reset();
      btnNovo.textContent = "+";
    }, 300);
  });
});

// Fechamento do formCarro através da tecla ESC
addEventListener("keydown", (e) => {
  if (e.key === "Escape" || e.key === "Esc" || e.keyCode === 27) {
    // Fechar o formulário de carros, se estiver aberto
    const formCarro = document.getElementById("form-carro");
    const btnNovo = document.getElementById("btn-novo-carro");
    if (formCarro && formCarro.classList.contains("show")) {
      formCarro.classList.remove("show");
      formCarro.reset();
      btnNovo.textContent = "+";
    }
  }
});


// ==============================================================================

// Exclusão de manutenções
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

// ==============================================================================


document.addEventListener("DOMContentLoaded", () => {
  const botaoAbrirModal = document.querySelectorAll(".abrir-modal-manutencao");
  const modal = document.getElementById("modalManutencao");
  const fecharModal = document.getElementById("fecharModalManutencao");


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
});

// ==============================================================================


// Carregar os tipos de manutenção
document.addEventListener("DOMContentLoaded", () => {
  // Carregar tipos assim que a página carrega
  carregarTipos("tipo");
  carregarFormaPagamento("forma_pagamento");
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

// ==============================================================================

// Carrega formas de pagamento
function carregarFormaPagamento(selectId, valorSelecionado = null) {
  const endpoint = '../actions/forma_pagamento.php?nocache=' + Date.now();

  return fetch(endpoint)
    .then(res => res.json())
    .then(data => {
      // Força formato array [{id, nome}]
      const formas = (Array.isArray(data) ? data : [])
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
      select.innerHTML = '<option value="">Selecione uma forma...</option>';

      let selecionou = false;

      formas.forEach(forma => {
        const opt = new Option(forma.nome, forma.id);
        if (valorSelecionado && String(forma.id) === String(valorSelecionado)) {
          opt.selected = true;
          selecionou = true;
        }
        select.add(opt);
      });

      // Se o valor salvo não existir mais na lista, adiciona-o e seleciona
      if (valorSelecionado && !selecionou) {
        const opt = new Option("⚠ Forma de pagamento removida", valorSelecionado, true, true);
        select.add(opt, 1);
      }
    })
    .catch(err => {
      console.error('Erro ao carregar formas de pagamento:', err);
    });
}

// ==============================================================================

// <!-- Script Menu Mobile -->
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

// ==============================================================================

// Modo Edição
const modal = document.getElementById("modalEdicao");
const closeBtn = modal.querySelector(".close");
const fecharModal = document.getElementById("fecharModalEdicao");

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
    document.getElementById("edit-prox-data").value = btn.dataset.proxData && btn.dataset.proxData !== "null" ? btn.dataset.proxData : "";
    document.getElementById("edit-prox-km").value = btn.dataset.proxKm && btn.dataset.proxKm !== "null" ? btn.dataset.proxKm : "";

    document.getElementById("edit-pago").checked = btn.dataset.pago === "1";

    // carregar tipos dinâmicos e já selecionar o atual
    carregarTipos("edit-tipo", btn.dataset.tipoId);
    // carregar formas de pagamento dinâmicos e já selecionar o atual
    carregarFormaPagamento("edit-form-pgto", btn.dataset.formPgto);

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

// ==============================================================================

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
    alerta.style.background = data.status === "success" ? "#bbffbb" : "#fcb7b7";



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
    alerta.style.background = "#fcb7b7";
    console.error(err);
  }
});

// ==============================================================================

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
    alertaEdicao.style.background = data.status === "success" ? "#bbffbb" : "#fcb7b7";

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
    alertaEdicao.style.background = "#fcbb7b";
    console.error(err);
  }
});

// ==============================================================================

// Visualizar manutenção
document.addEventListener('DOMContentLoaded', () => {
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

  // ESC fecha todos os detalhes
  document.addEventListener('keydown', e => {
    if (e.key === "Escape" || e.key === "Esc" || e.keyCode === 27) {
      document.querySelectorAll('tr').forEach(tr => {
        const detalhes = tr.querySelectorAll('.detalhes');
        const btn = tr.querySelector('.toggle-details');

        if (detalhes.length > 0) {
          detalhes.forEach(td => td.classList.add('hidden'));
        }
        if (btn) {
          btn.setAttribute('aria-expanded', 'false');
          btn.textContent = '👁️';
        }
      });
    }
  });
});




// ==============================================================================

// JavaScript (Toggle do Menu)
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const toggle = document.getElementById("toggleSidebar");

  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("expanded");
  });
});

// ==============================================================================

// Fechamento dos modais através da tecla ESC
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" || e.key === "Esc" || e.keyCode === 27) {

    document.querySelectorAll(".modal").forEach(modal => {
      if (!modal.classList.contains("hidden")) {
        modal.classList.add("hidden");
      }
    });
  }
});

// ==============================================================================




