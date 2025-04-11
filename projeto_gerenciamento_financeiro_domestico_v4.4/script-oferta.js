function mostrarCamposAdicionais() {
    let tipo = document.getElementById("tipo").value;
    let camposOferta = document.getElementById("camposOferta");

    if (tipo === "oferta") {
        camposOferta.style.display = "block";
        buscarSugestaoValor();
    } else {
        camposOferta.style.display = "none";
    }
}

function validarFormulario() {
    let tipo = document.getElementById("tipo").value;
    let valor = document.getElementById("valor").value;

    if (tipo === "oferta" && (valor === "" || parseFloat(valor) <= 0)) {
        alert("Por favor, informe um valor válido para a oferta.");
        return false;
    }

    return true;
}