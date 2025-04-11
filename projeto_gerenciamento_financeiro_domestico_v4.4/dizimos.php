<?php
include 'db_connection.php';
include 'header.php';

$receitas = [];
$despesas = [];
$totalReceitas = 0;
$totalDespesas = 0;
$saldoFinal = 0;
$dizimo = 0;
$oferta = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];

    $sql = "SELECT * FROM transacoes WHERE mes = :mes AND ano = :ano";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mes', $mes);
    $stmt->bindParam(':ano', $ano);
    $stmt->execute();
    $transacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($transacoes as $transacao) {
        if ($transacao['tipo'] === 'receita') {
            $receitas[] = $transacao;
            $totalReceitas += $transacao['valor'];
        } else {
            $despesas[] = $transacao;
            $totalDespesas += $transacao['valor'];
        }
    }

    $saldoFinal = $totalReceitas - $totalDespesas;

    $dizimo = $totalReceitas * 0.10;
    $oferta = $totalReceitas * 0.10;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro</title>

    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-form.css">
    <link rel="stylesheet" href="styles-tables.css">
    <link rel="stylesheet" href="style_form_contribuicao.css">
    <link rel="stylesheet" href="style_form_dizimo.css">
    <link rel="stylesheet" href="media_queries.css">

    <script src="scripts.js" defer></script>
    <script src="script-oferta.js" defer></script>
</head>

<body>
    <main>
        <h2>Consultar Receitas para Oferta</h2>
        <form action="dizimos.php" method="POST" class="form-contribuicao">

            <div class="mes">
                <label for="mes">Mês:</label>
                <select id="mes" name="mes" required>
                    <option value="">Selecione um mês...</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="ano">
                <label for="ano">Ano:</label>
                <input type="number" id="ano" name="ano" value="<?php echo date('Y'); ?>" required>
            </div>

            <button type="submit">Consultar</button>
        </form>

        <?php if (!empty($receitas) || !empty($despesas)): ?>
            <h1>Relatório de <?php echo $mes . '/' . $ano; ?></h1>

            <h2>Receitas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Forma de Pagamento</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($receitas as $receita): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($receita['nome']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($receita['data_vencimento'])); ?></td>
                            <td>R$ <?php echo number_format($receita['valor'], 2, ',', '.'); ?></td>
                            <td><?php echo $receita['forma_pagamento']; ?></td>
                            <td><?php echo htmlspecialchars($receita['descricao']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            Total de Receitas: R$ <?php echo number_format($totalReceitas, 2, ',', '.'); ?>
                        </td>

                    </tr>
                </tfoot>
            </table>
            <h1>Dízimos e Ofetas</h1>

            <form action="add_contribution.php" method="POST" onsubmit="return validarFormulario()" class="form-dizimo">
                <p><strong>Dízimo:</strong> R$ <?php echo number_format($dizimo, 2, ',', '.'); ?> </p>
                <input type="hidden" name="mes" value="<?php echo $mes; ?>">
                <input type="hidden" name="ano" value="<?php echo $ano; ?>">

                <label for="tipo">Escolha a contribuição:</label>
                <select id="tipo" name="tipo" onchange="mostrarCamposAdicionais()">
                    <option value="dizimo">Dízimo</option>
                    <option value="oferta">Oferta</option>
                </select>

                <div id="camposOferta" style="display: none;">
                    <label for="valor">Valor da Contribuição (Sugestão: <span id="sugestaoValor">0</span>):</label>
                    <input type="number" id="valor" name="valor" step="0.01" min="0">

                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Digite uma descrição">
                </div>

                <button type="submit">Adicionar Contribuição</button>
            </form>
        <?php endif; ?>
    </main>
    <?php include 'footer.php';?>


    <script>
        function buscarSugestaoValor() {
            let mes = "<?php echo $mes; ?>";
            let ano = "<?php echo $ano; ?>";

            fetch(`sugestao_valor.php?mes=${mes}&ano=${ano}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("sugestaoValor").textContent = data.sugestao;
                    document.getElementById("valor").value = data.sugestao;
                })
                .catch(error => console.error("Erro ao buscar sugestão de valor:", error));
        }
    </script>
</body>

</html>