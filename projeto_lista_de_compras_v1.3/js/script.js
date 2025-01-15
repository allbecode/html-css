const productList = document.getElementById('product-list')
const quantidade = document.getElementById('iquantidade')
const list = document.getElementById('selected-products-list')
const addButton = document.getElementById('addButton')


addButton.addEventListener('click', () => {
    const itemSelecionado = productList.value
    const qtde = quantidade.value

    // Validando as informações
    if (itemValido(itemSelecionado)) {
        alert('Por favor, selecione um produto.')
        productList.focus()
    } else if (qtdeValida(qtde)) {
        alert('Quantidade invávila, por favor digite a quantidade novamente.')
        quantidade.focus()
    } else if (!estaNaLista(itemSelecionado)) {
        addItensaLista(itemSelecionado)
    } else {
        alert('Este item já foi adicionado a lista. Por favor selecione outro produto.')
        productList.focus()
    }
})

// Validando o item selecionado
function itemValido(itemText) {
    if (itemText.length == 0) {
        return true
    }
    return false
}

// Validando a quantidade escolhida
function qtdeValida(itemText) {
    if (Number(itemText) <= 0) {
        return true
    }
    return false
}

// Verificando se o item selecionado já está na lista
function estaNaLista(itemText) {
    let items = list.querySelectorAll('tr td')
    for (let item of items) {
        if (item.textContent === itemText) {
            return true
        }
    }
    return false
}

// Adicioando um item à lista de compras
function addItensaLista(itemText, itemNumber) {
    let selectProduct = productList.options[productList.selectedIndex].value
    let row = document.createElement('tr')
    row.innerHTML += `<td id="tdProduto">${selectProduct}</td>`
    row.innerHTML += `<td id="tdQtde">${Number(quantidade.value)}</td>`
    row.innerHTML += `<td class="no-print">
                      <input type="button" value="Editar" class="btn editButton" id="editButton">
                      <input type="button" value="Excluir" class="btn del deleteButton">
                      </td>
                    `
    list.appendChild(row)
    productList.focus()
}

// Excluido itens da tabela
let tabelaDelete = document.querySelector('#selected-products-list')
tabelaDelete.addEventListener('click', (event) => {
    let elementoClicado = event.target
    if (elementoClicado.classList.contains('deleteButton')) {
        let celula = elementoClicado.parentNode
        let linha = celula.parentNode
        linha.remove()
    }
    productList.focus()
})

// Editando itens da tabela
let tabelaEdit = document.querySelector('#selected-products-list')
tabelaEdit.addEventListener('click', (event) => {
    let elementoClicado = event.target
    if (elementoClicado.classList.contains('editButton')) {
        let celula = elementoClicado.parentNode
        let linha = celula.parentNode
        let produto = linha.querySelector('#tdProduto')
        let qtde = linha.querySelector('#tdQtde')
        
        let newProduct = prompt('Digite o novo produto:', produto.textContent)
        if(newProduct != null){
           produto.innerHTML = newProduct            
        }

        let newQtde = prompt('Digite a nova quantidade:', qtde.textContent)
        if(newQtde != null){
            qtde.innerHTML = newQtde
        }
    }
    productList.focus()
})

// Imprimindo a lista de compras
function imprimirLista() {
    window.print()
    productList.focus()
}

window.onload = document.getElementById('product-list').focus()