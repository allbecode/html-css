<?php
include 'header.php';
$anoAtual = date('Y');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-lista-transacoes.css">
    <link rel="stylesheet" href="style_relatorio_contribuicao.css">
    <link rel="stylesheet" href="style-lista-de-compras.css">

    <script src="script-lista-de-compras.js" defer></script>

    <title>Lista de Compras</title>
</head>

<body>
    <main>
        <h2 class="no-print">Lista de Compras</h2>
        <form id="form-produto" class="form-filtro no-print">
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
                    <td></td>
                </tfoot>
            </table>
            <p id="mensagem-vazia" style="color: gray;">Nenhum item adicionado à lista.</p>
            <div id="botoes-acoes" class="no-print" style="display: none;">
                <button title="Imprimir Lista" class="btn-imprimir no-print" onclick="imprimirLista()">🖨️</button>
                <button title="Limpar Lista" class="btn-limpar no-print" id="btn-limpar-lista">🧹</button>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>