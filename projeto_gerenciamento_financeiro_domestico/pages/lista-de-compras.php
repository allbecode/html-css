<?php
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Lista de Compras</title> -->

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/lista-compras.css">
    <link rel="stylesheet" href="../assets/css/segmentation/form-global.css">
    
    <script src="../assets/js/script-lista-de-compras.js" defer></script>
    
</head>

<body>
    <main>
        <h2 class="no-print">Lista de Compras</h2>
        <form id="form-produto" class="form-geral no-print">
            <label for="produto">Produto:</label>
            <select id="produto" required>
                <option value="">Selecione um produto...</option>
                <!-- A lista suspensa de de produtos será carregada aqui. -->
            </select>
            <label for="quantidade">Qtd:</label>
            <input type="number" id="quantidade" min="1" value="1" required>
            <button type="submit">Selecionar</button>
        </form>
        <div class="container">
            <table id="tabela-compras">
                <thead>
                    <tr>
                        <th colspan="3" class="print">Lista de Compras</th>
                    </tr>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th class="no-print">Ação</th>
                    </tr>
                </thead>
                <tbody id="lista-produtos-selecionados">
                    <!-- Os produtos selacionados serão exibidos aqui -->
                </tbody>
                <tfoot>
                        <tr>
                            <td colspan="3"></td>
                        </tr>
                </tfoot>
            </table>
            <p id="mensagem-vazia" style="color: gray;">Nenhum item adicionado à lista.</p>
            <div id="botoes-acoes" class="no-print" style="display: none;">
                <button title="Imprimir Lista" class="button-icon no-print" onclick="imprimirLista()">🖨️</button>
                <button title="Limpar Lista" class="button-icon no-print" id="btn-limpar-lista">🧹</button>
            </div>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>

</html>