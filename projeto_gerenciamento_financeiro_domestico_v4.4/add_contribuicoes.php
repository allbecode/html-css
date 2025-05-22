<?php

// Falta:
// 
// 1- Eliminar os comentário indevidos;

include 'db_connection.php';
include 'header.php';

$mesSelecionado = $_POST['mes'] ?? date('m');
$anoSelecionado = $_POST['ano'] ?? date('Y');
$tipoSelecionado = $_POST['tipo_contribuicao'] ?? 'dizimo';

$totalReceitas = 0;
$valorEsperadoDizimo = 0;
$valorEsperadoOferta = 0;
$valorDizimado = 0;
$valorOfertado = 0;
$valorRestanteDizimo = 0;
$valorRestanteOferta = 0;
$mes = date('m');
$ano = date('Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];

    // Total de receitas
    $stmt = $pdo->prepare("SELECT SUM(valor) FROM transacoes WHERE tipo = 'receita' AND mes = :mes AND ano = :ano");
    $stmt->execute(['mes' => $mes, 'ano' => $ano]);
    $totalReceitas = $stmt->fetchColumn() ?: 0;

    // Verifica se há receitas
    $temReceitas = $totalReceitas > 0;

    // Dízimo
    $valorEsperadoDizimo = $totalReceitas * 0.10;
    $stmt = $pdo->prepare("SELECT SUM(valor) FROM transacoes WHERE tipo = 'despesa' AND nome = 'Dízimo' AND mes = :mes AND ano = :ano");
    $stmt->execute(['mes' => $mes, 'ano' => $ano]);
    $valorDizimado = $stmt->fetchColumn() ?: 0;
    $valorRestanteDizimo = max($valorEsperadoDizimo - $valorDizimado, 0);

    // Oferta
    $valorEsperadoOferta = $totalReceitas * 0.10;
    $stmt = $pdo->prepare("SELECT SUM(valor) FROM transacoes WHERE tipo = 'despesa' AND nome = 'Oferta' AND mes = :mes AND ano = :ano");
    $stmt->execute(['mes' => $mes, 'ano' => $ano]);
    $valorOfertado = $stmt->fetchColumn() ?: 0;
    $valorRestanteOferta = max($valorEsperadoOferta - $valorOfertado, 0);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Contribuições - Dízimos e Ofertas</title>
    <link rel="stylesheet" href="styles-principal.css">
    <link rel="stylesheet" href="style-form.css">
    <link rel="stylesheet" href="styles-tables.css">
    <link rel="stylesheet" href="style_relatorio_contribuicao.css">
    <link rel="stylesheet" href="style-lista-transacoes.css">
    <link rel="stylesheet" href="media_queries.css">

    <script src="script-contribuicoes.js" defer></script>
    <script src="script-ajax.js" defer></script>
    <script src="script-form.js"></script>
    <!-- <script src="scripts.js"></script> -->
</head>

<body>
    <main>
        <h2>Contribuições - <?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . "/" . $ano; ?></h2>
        <div class="container-form">
            <p>Altere uma das opções abaixo para visualizar os dados. </p>
            <!-- <form method="POST" action="" class="form-filtro" id="form-contribuicao" data-origem="contribuicao"> -->
            
            <form method="POST" class="form-filtro" id="form-contribuicao" data-origem="contribuicao">
                
                <label for="mes">Mês:</label>
                <select name="mes" id="mes" required>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $mes ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endfor; ?>
                </select>
                <label for="ano">Ano:</label>
                <input type="number" name="ano" id="ano" value="<?= $ano ?>" required>
                <label for="tipo_contribuicao">Tipo de Contribuição:</label>
                <select id="tipo_contribuicao" name="tipo_contribuicao" required>
                    <option value="dizimo" <?= ($tipoSelecionado == 'dizimo') ? 'selected' : '' ?>>Dízimo</option>
                    <option value="oferta" <?= ($tipoSelecionado == 'oferta') ? 'selected' : '' ?>>Oferta</option>
                </select>
                <!-- <button type="submit">Consultar</button> -->
            </form>
        </div>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php if (!$temReceitas): ?>
                <div class="container">
                    <p class="status-nao-pago">✖ Nenhuma receita cadastrada no mês <strong><?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . "/" . $ano; ?></strong>.<br>
                        Nenhuma contribuição pode ser calculada ou registrada.</p>
                </div>
            <?php else : ?>
                <div class="form-lcto-contribuicao">
                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['tipo_contribuicao'], ['dizimo', 'oferta'])):
                        $tipoSelecionado = $_POST['tipo_contribuicao'];
                        $nomeContribuicao = ucfirst($tipoSelecionado);
                        $valorSugerido = $tipoSelecionado === 'dizimo' ? $valorRestanteDizimo : $valorRestanteOferta;
                    ?>
                        <h2>Histórico de Contribuições - <?= $nomeContribuicao ?> - <?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . "/" . $ano;?></h2>
                        <div class="container">
                            <div id="resumo_dizimo" class="resumo-box">
                                <h3>Resumo</h3>
                                <ul>
                                    <li><strong>Receitas do mês:</strong> R$ <?= number_format($totalReceitas, 2, ',', '.') ?></li>
                                    <li><strong>Dízimo sugerido (10%):</strong> R$ <?= number_format($valorEsperadoDizimo, 2, ',', '.') ?></li>
                                    <li><strong>Valor já dizimado:</strong> R$ <?= number_format($valorDizimado, 2, ',', '.') ?></li>
                                    <li><strong>Valor restante a dizimar:</strong> R$ <?= number_format($valorRestanteDizimo, 2, ',', '.') ?></li>
                                </ul>
                            </div>
                            <div id="resumo_oferta" class="resumo-box">
                                <h3>Resumo</h3>
                                <ul>
                                    <li><strong>Receitas do mês:</strong> R$ <?= number_format($totalReceitas, 2, ',', '.') ?></li>
                                    <li><strong>Oferta sugerida (10%)</strong>: R$ <?= number_format($valorEsperadoOferta, 2, ',', '.') ?></li>
                                    <li><strong>Valor já ofertado:</strong> R$ <?= number_format($valorOfertado, 2, ',', '.') ?></li>
                                    <li><strong>Valor restante para ofertar:</strong> R$ <?= number_format($valorRestanteOferta, 2, ',', '.') ?></li>
                                </ul>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Descrição</th>
                                        <th>Valor</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT data_vencimento, descricao, valor FROM transacoes
        WHERE tipo = 'despesa' AND nome = :nome AND mes = :mes AND ano = :ano ORDER BY data_vencimento ASC");
                                    $stmt->execute(['nome' => $nomeContribuicao, 'mes' => $mes, 'ano' => $ano]);
                                    $contribuicoes = $stmt->fetchAll();

                                    if (count($contribuicoes) > 0):
                                        foreach ($contribuicoes as $contrib): ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($contrib['data_vencimento'])) ?></td>
                                                <td><?= htmlspecialchars($contrib['descricao']) ?></td>
                                                <td>R$ <?= number_format($contrib['valor'], 2, ',', '.') ?></td>
                                                <td>
                                                    <a href="relatorio_individual_contribuicao.php?mes=<?= $mes ?>&ano=<?= $ano ?>&nome=<?= $nomeContribuicao ?>&valor_dizimo=<?= $contrib['valor'] ?>&descricao=<?= $contrib['descricao'] ?>" target="_blank">
                                                        <button>Reimprimir</button>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    else: ?>
                                        <tr>
                                            <td colspan="4" style="text-align: center; padding: 10px;">
                                                Nenhuma contribuição registrada para <strong><?= $nomeContribuicao ?></strong> em <strong><?= "$mes/$ano" ?></strong>.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <?php $temContribuicoes = count($contribuicoes) > 0; ?>
                            <?php if ($temContribuicoes): ?>
                                <a href="relatorio_global_contribuicao.php?mes=<?= $mesSelecionado ?>&ano=<?= $anoSelecionado ?>" target="_blank">
                                    <button>Imprimir Relatório Global</button>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if ($valorSugerido <= 0 && $tipoSelecionado === 'dizimo'): ?>
                            <hr>
                            <p><strong>⚠️ Você já atingiu o limite de 10% para <?= $nomeContribuicao ?>s neste mês.</strong></p>
                            <hr>
                        <?php else: ?>
                            <?php if ($valorSugerido <= 0 && $tipoSelecionado === 'oferta'): ?>
                                <hr>
                                <p><strong>⚠️ Você já atingiu o limite de 10% para <?= $nomeContribuicao ?>s neste mês.</strong></p>
                                <hr>
                            <?php endif; ?>
                            <h2>Lançar nova contribuição : <?= $nomeContribuicao ?> - <?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . "/" . $ano;?></h2>

                            <div class="container-form">
                                <form id="form-transacao" method="POST" action="add_transaction.php" class="form-filtro" data-origem="contribuicao">
                                    <input type="hidden" name="mes" value="<?= $mes ?>">
                                    <input type="hidden" name="ano" value="<?= $ano ?>">
                                    <input type="hidden" name="tipo" value="despesa">
                                    <input type="hidden" name="nome" value="<?= $nomeContribuicao ?>">
                                    <input type="hidden" name="data_vencimento" value="<?= $ano . '-' . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01' ?>">
                                    <label>Valor:</label>
                                    <input type="number" name="valor" step="0.01" value="<?= number_format($valorSugerido, 2, '.', '') ?>" required>
                                    <label>Forma de Pagamento:</label>
                                    <select name="forma_pagamento" required>
                                        <option value="PIX">PIX</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Boleto Bancário">Boleto Bancário</option>
                                        <option value="Débito em Conta">Débito em Conta</option>
                                    </select>
                                    <label>Descrição:</label>
                                    <input type="text" name="descricao" required>
                                    <input type="hidden" name="pago" value="0">
                                    <button type="submit">Salvar Contribuição</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <?php include 'footer.php' ?>

</body>

</html>