function marcarComoPago(id) {
    if (confirm("Tem certeza que deseja marcar esta despesa como paga?")) {
        fetch("marcar_pago.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" + id
        })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                alert("Despesa marcada como paga!");
                location.reload();
            } else {
                alert("Erro ao marcar despesa como paga.");
            }
        });
    }
}

function mudouTamanho(){
    if (window.innerWidth >= 768) {
        itens.style.display = 'block'
    }else{
        itens.style.display = 'none'
    }
}


function clickMenu() {
   if (itens.style.display == 'block') {
         itens.style.display = "none"
   }else{
         itens.style.display = 'block'
   }
}

function anoAtual() {
    let ano = document.getElementById('ano');
    let anoAtual = new Date().getFullYear();
    ano.innerHTML += `${anoAtual}`;
}

