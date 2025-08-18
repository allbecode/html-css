<?php

require_once '../acsses_control/includes/auth.php';
require_once '../acsses_control/includes/session.php';
require_once '../acsses_control/includes/functions.php';
require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';

// Garante que o usuário está logado
verificaUsuarioLogado();

// Recupera o ID do usuário logado (compatível com o que é definido no login.php)
$usuarioId = $_SESSION['usuario_id'] ?? $_SESSION['id'] ?? null;

include '../includes/header.php';
require_once '../controllers/controller_contribuicoes.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Form Add Contribuições</title> -->

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/form-global.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-contribuicao.css">

    <script src="../assets/js/utils.js" defer></script>
    <script src="../assets/js/script-ajax.js" defer></script>
    <script src="../assets/js/script-contribuicoes.js" defer></script>
    <script src="../assets/js/script-form.js" defer></script>

</head>

<body>
    <main>
        <h2>Contribuições - <?php echo formatarMesEAno($mes, $ano); ?></h2>
        <div class="container-form">

            <p class="center">Altere uma das opções abaixo para visualizar os dados. </p>
            <form method="POST" class="form-geral" id="form-geral" data-origem="contribuicao">

                <label for="mes">Mês:</label>
                <select name="mes" id="mes" required>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $mes ? 'selected' : '' ?>><?= str_pad($m, 2, '0', STR_PAD_LEFT) ?></option>
                    <?php endfor; ?>
                </select>
                <label for="ano">Ano:</label>
                <input type="number" name="ano" id="ano" value="<?= $ano ?>" required>
                <label for="tipo_contribuicao">Tipo de Contribuição:</label>
                <select id="tipo_contribuicao" name="tipo_contribuicao" required>
                    <option value="dizimo" <?= ($tipoSelecionado == 'dizimo') ? 'selected' : '' ?>>Dízimo</option>
                    <option value="oferta" <?= ($tipoSelecionado == 'oferta') ? 'selected' : '' ?>>Oferta</option>
                </select>
            </form>
        </div>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php if (!$temReceitas): ?>
                <div class="mensagem-sem-dados">
                    <p>
                        ✖ Nenhuma receita cadastrada no mês <strong><?php echo formatarMesEAno($mes, $ano); ?></strong>.<br>Portanto, nenhuma contribuição pôde ser calculada ou registrada.
                    </p>
                </div>
            <?php else : ?>

                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_POST['tipo_contribuicao'], ['dizimo', 'oferta'])):
                    $tipoSelecionado = $_POST['tipo_contribuicao'];
                    $nomeContribuicao = ucfirst($tipoSelecionado);
                    $valorSugerido = $tipoSelecionado === 'dizimo' ? $valorRestanteDizimo : $valorRestanteOferta;
                ?>
                    <h2>Histórico de Contribuições - <?= $nomeContribuicao ?> - <?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . "/" . $ano; ?></h2>
                    <div class="container">
                        <div id="resumo_dizimo" class="resumo-box">
                            <h3>Resumo</h3>
                            <ul>
                                <li><strong>Receitas do mês:</strong> <?php echo formatarValor($totalReceitas) ?></li>
                                <li><strong>Dízimo sugerido (10%):</strong> <?php echo formatarValor($valorEsperadoDizimo) ?></li>
                                <li><strong>Valor já dizimado:</strong> <?php echo formatarValor($valorDizimado) ?></li>
                                <li><strong>Valor restante a dizimar:</strong> <?php echo formatarValor($valorRestanteDizimo) ?></li>
                            </ul>
                        </div>
                        <div id="resumo_oferta" class="resumo-box">
                            <h3>Resumo</h3>
                            <ul>
                                <li><strong>Receitas do mês:</strong> <?php echo formatarValor($totalReceitas) ?></li>
                                <li><strong>Oferta sugerida (10%):</strong> <?php echo formatarValor($valorEsperadoOferta) ?></li>
                                <li><strong>Valor já ofertado:</strong> <?php echo formatarValor($valorOfertado) ?></li>
                                <li><strong>Valor restante para ofertar:</strong> <?php echo formatarValor($valorRestanteOferta) ?></li>
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
                                $stmt = $pdo->prepare("SELECT data_vencimento, 
                                descricao, 
                                valor 
                                FROM transacoes
                                WHERE tipo = 'despesa' 
                                AND usuario_id = :usuario_id 
                                AND nome = :nome 
                                AND mes = :mes 
                                AND ano = :ano 
                                ORDER BY data_vencimento ASC"
                                );
                                $stmt->execute(['usuario_id' => $usuarioId, 'nome' => $nomeContribuicao, 'mes' => $mes, 'ano' => $ano]);
                                $contribuicoes = $stmt->fetchAll();

                                if (count($contribuicoes) > 0):
                                    foreach ($contribuicoes as $contrib): ?>
                                        <tr>
                                            <td><?php echo formatarDataBr($contrib['data_vencimento']) ?></td>
                                            <td><?= htmlspecialchars($contrib['descricao']) ?></td>
                                            <td> <?php echo formatarValor($contrib['valor']) ?></td>
                                            <td>
<a href="../reports/relatorio_individual_contribuicao.php?mes=<?= $mes ?>&ano=<?= $ano ?>&nome=<?= $nomeContribuicao ?>&valor_dizimo=<?= $contrib['valor'] ?>&descricao=<?= $contrib['descricao'] ?>" target="_blank">
                                                    <button title="Imprimir Relatório Individual" class="button-icon">🖨️</button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 10px;">
                                            Nenhuma contribuição registrada como <strong><?= $nomeContribuicao ?></strong> em <strong><?= "$mes/$ano" ?></strong>.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <?php $temContribuicoes = count($contribuicoes) > 0; ?>
                        <?php if ($temContribuicoes): ?>
                            <a href="../reports/relatorio_global_contribuicao.php?mes=<?= $mesSelecionado ?>&ano=<?= $anoSelecionado ?>" target="_blank">
                                <button>Imprimir Relatório Global</button>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if ($valorSugerido <= 0 && $tipoSelecionado === 'dizimo'): ?>
                        <hr>
                        <p class="center"><strong>⚠️ Você já atingiu o limite de 10% para <?= $nomeContribuicao ?>s neste mês.</strong></p>
                        <hr>
                    <?php else: ?>
                        <?php if ($valorSugerido <= 0 && $tipoSelecionado === 'oferta'): ?>
                            <hr>
                            <p class="center"><strong>⚠️ Você já atingiu o limite de 10% para <?= $nomeContribuicao ?>s neste mês.</strong></p>
                            <hr>
                        <?php endif; ?>
                        <h2>Lançar nova contribuição : <?= $nomeContribuicao ?> - <?php echo str_pad($mes, 2, '0', STR_PAD_LEFT) . "/" . $ano; ?></h2>

                        <div class="container-form">
                            <form id="form-geral" method="POST" action="../actions/add_transaction.php" class="form-geral" data-origem="contribuicao">
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
                                <button title="Salvar Contribuição" class="button-icon" type="submit">💾</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            <?php endif; ?>
        <?php endif; ?>
    </main>
    <?php include '../includes/footer.php' ?>
</body>

</html>