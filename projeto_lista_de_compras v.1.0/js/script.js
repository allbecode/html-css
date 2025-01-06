
// scriptIndex - Início

let selectedProducts = [];

// Função para carregar o arquivo JSON e preencher a lista suspensa
async function loadProducts() {
    try {
        const response = await fetch('produtos.json'); // Certifique-se de que o arquivo produtos.json está no mesmo diretório
        if (!response.ok) {
            throw new Error('Erro ao carregar o arquivo JSON');
        }

        const products = await response.json();
        const dropdown = document.getElementById('product-dropdown');
        dropdown.innerHTML = '<option value="">Selecione um produto</option>';

        products.forEach(product => {
            const option = document.createElement('option');
            option.value = product.name;
            option.textContent = `${product.name} - ${product.category}`;
            option.dataset.category = product.category;
            dropdown.appendChild(option);
        });
    } catch (error) {
        console.error(error);
        alert('Não foi possível carregar a lista de produtos.');
    }
}

function addToSelectedList() {
    const dropdown = document.getElementById('product-dropdown');
    const selectedOption = dropdown.options[dropdown.selectedIndex];

    if (selectedOption.value) {
        const product = {
            name: selectedOption.value,
            category: selectedOption.dataset.category
        };
        selectedProducts.push(product);
        renderSelectedProducts();
    } else {
        alert('Por favor, selecione um produto.');
    }
}

function renderSelectedProducts() {
    const list = document.getElementById('selected-products-list');
    list.innerHTML = '';
    selectedProducts.forEach((product, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.category}</td>
                    <td>
                        <input type="number" step="0.1" minlength="2" placeholder="Qtde">
                    </td>
                    <td>
                        <button class="btn" onclick="editSelectedProduct(${index})">Editar</button>
                        <button id="del" class="btn" onclick="deleteSelectedProduct(${index})">Excluir</button>
                    </td>
                `;
        list.appendChild(row);
    });
}

function editSelectedProduct(index) {
    const newName = prompt('Editar Nome do Produto:', selectedProducts[index].name);
    const newCategory = prompt('Editar Categoria:', selectedProducts[index].category);
    if (newName && newCategory) {
        selectedProducts[index].name = newName;
        selectedProducts[index].category = newCategory;
        renderSelectedProducts();
    }
}

function deleteSelectedProduct(index) {
    if (confirm('Deseja realmente excluir este produto?')) {
        selectedProducts.splice(index, 1);
        renderSelectedProducts();
    }
}

function saveSelectedProducts() {
    const json = JSON.stringify(selectedProducts, null, 2);
    const blob = new Blob([json], { type: 'application/json' });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = 'selected_products.json';
    a.click();
}

function printSelectedProducts() {
    // const printableContent = selectedProducts.map(product => `${product.name} - ${product.category}`).join('\n');
    // const newWindow = window.open('', '', 'width=600,height=400');
    // newWindow.document.write(`<pre>${printableContent}</pre>`);
    // newWindow.print();

    // Imprimir lista
    
        window.print();
    
}

document.getElementById('add-to-list').addEventListener('click', addToSelectedList);
document.getElementById('save-list').addEventListener('click', saveSelectedProducts);
document.getElementById('print-list').addEventListener('click', printSelectedProducts);

// Carregar produtos ao carregar a página
window.onload= document.getElementById('product-dropdown').focus()
window.onload = loadProducts;


// scriptIndex - Fim

// scriptListaDeProdutos - Início



// scriptListaDeProdutos - Fim
