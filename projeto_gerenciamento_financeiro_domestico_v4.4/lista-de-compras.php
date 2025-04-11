<?php
include 'header.php';
$anoAtual = date('Y');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-form.css">
    <link rel="stylesheet" href="styles-tables.css">
    <link rel="stylesheet" href="style-lista-de-compras.css">
    <link rel="stylesheet" href="style_relatorio_dizimo.css">
    <link rel="stylesheet" href="style_form_contribuicao.css">
    <link rel="stylesheet" href="style_form_dizimo.css">
    <link rel="stylesheet" href="style_media_queries.css">

    <script src="script-lista-de-compras.js" defer></script>

    <title>Lista de Compras</title>
</head>

<body>
    <main>
        <h2>Lista de Compras</h2>
        <div class="no-print">
            <form action="" class="form-geral">

                <label for="product-list">Produtos: </label>
                <select name="product-list" id="product-list">
                    <option value="">Selecione um produto...</option>
                    <optgroup label="Mercearia">
                        <option value="Achocolatado (un)">Achocolatado (un)</option>
                        <option value="Açucar 1kg">Açucar 1kg</option>
                        <option value="Amido de milho (un)">Amido de milho (un)</option>
                        <option value="Arroz 1kg">Arroz 1kg</option>
                        <option value="Arroz 5kg">Arroz 5kg</option>
                        <option value="Aveia em flocos (un)">Aveia em flocos (un)</option>
                        <option value="Azeite de Oliva (un)">Azeite de Oliva (un)</option>
                        <option value="Banha de Porco (un)">Banha de Porco (un)</option>
                        <option value="Bicarbonato de Sódio (un)">Bicarbonato de Sódio (un)</option>
                        <option value="Café 500g">Café 500g</option>
                        <option value="Café Solúvel(un)">Café Solúvel(un)</option>
                        <option value="Chá(un)">Chá(un)</option>
                        <option value="Chocolate em pó (un)">Chocolate em pó (un)</option>
                        <option value="Côco Ralado(un)">Côco Ralado(un)</option>
                        <option value="Creme de Leite(un)">Creme de leite(un)</option>
                        <option value="Farinha de Mandióca 1kg">Farinha de Mandióca 1kg</option>
                        <option value="Farinha de Rosca (gramas)">Farinha de Rosca (gramas)</option>
                        <option value="Farinha de trigo 1kg">Farinha de trigo 1kg</option>
                        <option value="Farofa Pronta (un)">Farofa Pronta (un)</option>
                        <option value="Feijão 1kg">Feijão 1kg</option>
                        <option value="Fermento biológico(un)">Fermento biológico(un)</option>
                        <option value="Fermento quimico(un)">Fermento quimico(un)</option>
                        <option value="Fubá 1kg">Fubá 1kg</option>
                        <option value="Gelatina(un)">Gelatina(un)</option>
                        <option value="Ketchup(un)">Ketchup(un)</option>
                        <option value="Leite condensado(un)">Leite condensado(un)</option>
                        <option value="Macarrão 500g">Macarrão 500g</option>
                        <option value="Macarrão 1kg">Macarrão 1kg</option>
                        <option value="Maionese (un)">Maionese (un)</option>
                        <option value="Molho de tomate(un)">Molho de tomate(un)</option>
                        <option value="Mostarda(un)">Mostarda(un)</option>
                        <option value="Óleo de soja 1L">Óleo de soja 1L</option>
                        <option value="Polvilho Doce">Polvilho Doce</option>
                        <option value="Polvilho Azedo">Povilho Azedo</option>
                        <option value="Sal 1kg">Sal 1kg</option>
                        <option value="Sal grosso 1kg">Sal grosso 1kg</option>
                        <option value="Tapióca">Tapióca</option>
                        <option value="Temperos(un)">Temperos(un)</option>
                        <option value="Vinagre de Alcool 1L">Vinagre de Alcool 1L</option>
                        <option value="Vinagre de Maçã 1L">Vinagre de Maçã 1L</option>
                    </optgroup>
                    <optgroup label="Higiene pessoal">
                        <option value="Band-Aid (un)">Band-Aid (un)</option>
                        <option value="Barbeador (un)">Barbeador (un)</option>
                        <option value="Condicionador (un)">Condicionador (un)</option>
                        <option value="Cotonetes (un)">Cotonetes (un)</option>
                        <option value="Creme de Barbear (un)">Creme de Barbear (un)</option>
                        <option value="Creme Dental 180g">Creme Dental</option>
                        <option value="Creme para pentear (un)">Creme para pentear (un)</option>
                        <option value="Desodorante (un)">Desodorante (un)</option>
                        <option value="Escova Dental (un)">Escova Dental (un)</option>
                        <option value="Fio Dental (un)">Fio Dental (un)</option>
                        <option value="Papel Higiênico c/12">Papel Higiênico c/ 12</option>
                        <option value="Papel Higiênico c/16">Papel Higiênico c/ 16</option>
                        <option value="Papel Higiênico c/24">Papel Higiênico c/ 24</option>
                        <option value="Papel Higiênico c/40">Papel Higiênico c/ 40</option>
                        <option value="Repelente">Repelente</option>
                        <option value="Sabonete (un)">Sabonete (un)</option>
                        <option value="Sabonete Liquido (un)">Sabonete Liquido (un)</option>
                        <option value="Shampoo (un)">Shampoo (un)</option>
                    </optgroup>
                    <optgroup label="Limpeza">
                        <option value="Água Sanitária c/2L">Água Sanitária c/ 2 Litros</option>
                        <option value="Água Sanitária c/5L">Água Sanitária c/ 5 Litros</option>
                        <option value="Álcool 46 graus">Álcool 46 graus</option>
                        <option value="Álcool 70 graus">Álcool 70 graus</option>
                        <option value="Amaciante">Amaciante</option>
                        <option value="CIF (un)">CIF (un)</option>
                        <option value="Cloro Ativo (un)">Cloro Ativo (un)</option>
                        <option value="Desinfetante (un)">Desinfetante (un)</option>
                        <option value="Detergente (un)">Detergente (un)</option>
                        <option value="Detergente Multiuso (un)">Detergente Multiuso (un)</option>
                        <option value="Esponja (un)">Esponja (un)</option>
                        <option value="Esponja de aço (un)">Esponja de aço (un)</option>
                        <option value="Inseticida (un)">Inseticida (un)</option>
                        <option value="Limpador de Pisos (un)">Limpador de Pisos (un)</option>
                        <option value="Lustra móveis (un)">Lustra móveis (un)</option>
                        <option value="Óleo de Peróba (un)">Óleo de Peróba (un)</option>
                        <option value="Pedra Sanitária (un)">Pedra Sanitária (un)</option>
                        <option value="Removedor de Gordura (un)">Removedor de Gordura (un)</option>
                        <option value="Sabão em Pedra (un)">Sabão em pedra (un)</option>
                        <option value="Sabão em pó 2kg">Sabão em pó 2kg</option>
                        <option value="Sabão em pó 4kg">Sabão em pó 4kg</option>
                        <option value="Sabão Lava Roupas Liquido (un)">Sabão Lava Roupas Liquido (un)</option>
                        <option value="Saco para lixo 30L">Saco para lixo 30L</option>
                        <option value="Saco para lixo 100L">Saco para lixo 100L</option>
                    </optgroup>
                    <optgroup label="Bebidas">
                        <option value="Refrigerante Coca-Cola">Refrigerante Coca-Cola</option>
                        <option value="Refrigerante Outros">Refrigerante Outros</option>
                        <option value="Suco Liquido">Suco Liquido</option>
                        <option value="Suco em pó">Suco em pó</option>
                    </optgroup>
                    <optgroup label="Padaria e Doces">
                        <option value="Biscoito">Biscoito</option>
                        <option value="Bolo">Bolo</option>
                        <option value="Pão">Pão</option>
                        <option value="Pão doce">Pão doce</option>
                        <option value="Salgadinhos">Salgadinhos</option>
                        <option value="Torta doce">Torta doce</option>
                        <option value="Torta salgada">Torta salgada</option>
                    </optgroup>
                    <optgroup label="Utilidades e Outros">
                        <option value="Comida para Pássaros">Comida para Pássaros</option>
                        <option value="Corda p/ Varal">Corda p/ Varal</option>
                        <option value="Dispenser p/ Sabonete Liquido">Dispenser p/ Sabonete Liquido</option>
                        <option value="Espanador de Móveis">Espanador de Móveis</option>
                        <option value="Espanador de Teto">Espanador de Teto</option>
                        <option value="Filtro para café">Filtro para café</option>
                        <option value="Fósforos">Fósforos</option>
                        <option value="Lâmpada">Lâmpada</option>
                        <option value="Papel Alumínio">Papel Alumínio</option>
                        <option value="Papel toalha">Papel toalha</option>
                        <option value="Prendedores de Roupas">Prendedores de Roupas</option>
                        <option value="Rodo">Rodo</option>
                        <option value="Rodo de Pia">Rodo de Pia</option>
                        <option value="Saco para Congelar">Saco para Congelar</option>
                        <option value="Vassoura">Vassoura</option>
                        <option value="Velas">Velas</option>
                    </optgroup>
                    <optgroup label="Frutas">
                        <option value="Abacaxi kg">Abacaxi kg</option>
                        <option value="Banana da Terra kg">Banana da Terra kg</option>
                        <option value="Banana D'Água kg">Banana D'Água kg</option>
                        <option value="Banana prata kg">Banana prata kg</option>
                        <option value="Laranja Lima kg">Laranja Lima kg</option>
                        <option value="Laranja Pêra kg">Laranja Pêra kg</option>
                        <option value="Limão kg">Limão kg</option>
                        <option value="Maça Fuji kg">Maçã Fuji kg</option>
                        <option value="Maçã Gala kg">Maçã Gala kg</option>
                        <option value="Melancia kg">Melancia kg</option>
                        <option value="Melão kg">Melão kg</option>
                        <option value="Tangerina kg">Tangerina kg</option>
                    </optgroup>
                    <optgroup label="Legumes">
                        <option value="Aipim kg">Aipim kg</option>
                        <option value="Alho kg">Alho kg</option>
                        <option value="Batata kg">Batata kg</option>
                        <option value="Batata Doce kg">Batata Doce kg</option>
                        <option value="Berinjela kg">Berinjela kg</option>
                        <option value="Beterraba kg">Beterraba kg</option>
                        <option value="Cebola kg">Cebola kg</option>
                        <option value="Chalotas kg">Cebola (Chalotas) kg</option>
                        <option value="Cenoura kg">Cenoura kg</option>
                        <option value="Chuchu kg">Chuchu kg</option>
                        <option value="Inhame kg">Inhame kg</option>
                        <option value="Jiló kg">Jiló kg</option>
                        <option value="Pepino kg">Pepino kg</option>
                        <option value="Pimentão kg">Pimentão kg</option>
                        <option value="Tomate kg">Tomate kg</option>
                    </optgroup>
                    <optgroup label="Hortaliças">
                        <option value="Acelga">Acelga</option>
                        <option value="Agrião">Agrião</option>
                        <option value="Alface">Alface</option>
                        <option value="Cebolinha">Cebolinha</option>
                        <option value="Coentro">Coentro</option>
                        <option value="Hortelã">Hortelã</option>
                        <option value="Manjericão">Manjericão</option>
                        <option value="Repolho">Repolho</option>
                        <option value="Salsa">Salsa</option>
                    </optgroup>
                    <optgroup label="Frios e Laticínios">
                        <option value="Cream Cheese">Cream Cheese</option>
                        <option value="Fermento Fresco">Fermento Fresco</option>
                        <option value="Iogurte">Iogurte</option>
                        <option value="Leite 1L">Leite 1L </option>
                        <option value="Manteiga">Manteiga</option>
                        <option value="Margarina">Margarina</option>
                        <option value="Margarina p/ bolo">Margarina p/ bolo</option>
                        <option value="Mortadela defumada (gramas)">Mortadela defumada (gramas)</option>
                        <option value="Peito de peru defumada (gramas)">Peito de peru defumado (gramas)</option>
                        <option value="Presunto (gramas)">Presunto (gramas)</option>
                        <option value="Queijo Fresco (gramas)">Queijo Fresco (gramas)</option>
                        <option value="Queijo Mussarela (gramas)">Queijo Mussarela (gramas)</option>
                        <option value="Queijo Prato (gramas)">Queijo Prato (gramas)</option>
                        <option value="Requeijão">Requeijão</option>
                        <option value="Salame (gramas)">Salame (gramas)</option>
                    </optgroup>
                    <optgroup label="Carnes">
                        <option value="Bacon (gramas)">Bacon (gramas)</option>
                        <option value="Carne de Frango (kg)">Carna de Frango (kg)</option>
                        <option value="Carne Bovina (kg)">Carne Bovina (kg)</option>
                        <option value="Carne Seca (kg)">Carne Seca (kg)</option>
                        <option value="Carne Suina (kg)">Carne Suina (kg)</option>
                        <option value="Emp. de Frango Steak (um)">Emp. de Frango Steak (un)</option>
                        <option value="Hamburger (un)">Hamburger (un)</option>
                        <option value="Lingüiça Calabresa (kg)">Lingüiça Calabresa (kg)</option>
                        <option value="Lingüiça Suina (kg)">Lingüiça Suina (kg)</option>
                        <option value="Lingüiça Suina Fina (kg)">Lingüiça Suina Fina (kg)</option>
                        <option value="Lombo Suino (kg)">Lombo Suino (kg)</option>
                        <option value="Nuggets (kg)">Nuggets (kg)</option>
                        <option value="Orelha Suina (kg)">Orelha Suina (kg)</option>
                        <option value="Ovos c/20">Ovos c/ 20</option>
                        <option value="Ovos c/30">Ovos c/ 30</option>
                        <option value="Paio (kg)">Paio (kg)</option>
                        <option value="Pé Suino (kg)">Pé Suino (kg)</option>
                        <option value="Peixe (kg)">Peixe (kg)</option>
                        <option value="Rabo Suino (kg)">Rabo Suino (kg)</option>
                    </optgroup>
                </select>

                <label for="quantidade">Quantidade: </label>
                <input type="number" name="quantidade" id="iquantidade" step="0.1" min="0" value="1">

                <input type="button" value="Selecionar" class="btn" id="addButton">
            </form>
        </div>

        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th class="no-print">Ação</th>
                    </tr>
                </thead>
                <tbody id="selected-products-list">
                    <!-- Os produtos selacionados serão xibidos aqui -->
                </tbody>
            </table>
            <button onclick="imprimirLista()">Imprimir Lista</button>

        </div>

    </main>
    <?php include 'footer.php';?>
</body>

</html>