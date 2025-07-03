<?php
require_once '../controllers/controller_index.php';
require_once '../includes/functions.php';
include '../includes/header.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Página Inicial</title> -->

    <!-- <link rel="stylesheet" href="../assets/css/styles-principal.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/styles-tables.css"> -->
    <!-- <link rel="stylesheet" href="../assets/css/media_queries.css"> -->

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">

    <script src="../assets/js/script-index.js" defer></script>
</head>

<body>
    <main>

        <h3>Despesas Vencidas (Mês Atual: <?php echo "$mesAtual/$anoAtual" ?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>Vencimento</th>
                    <th>Forma de Pgto</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody id="tbody-vencidas">

                <?php if (!empty($despesasVencidas)): ?>
                    <?php foreach ($despesasVencidas as $despesa): ?>
                        <tr>
                            <td><?php echo formatarDataBr($despesa['data_vencimento']); ?></td>
                            <td><?php echo $despesa['forma_pagamento']; ?></td>
                            <td><?php echo htmlspecialchars($despesa['nome']); ?></td>
                            <td> <?php echo formatarValor($despesa['valor']); ?></td>
                            <td>
                                <button class="btn-marcar" data-id="<?php echo $despesa['id']; ?>" data-valor="<?php echo $despesa['valor']; ?>">Marcar como Paga</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma despesa vencida este mês.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tr id="msg-sem-vencidas">
                <td colspan="5">Nenhuma despesa vencida este mês.</td>
            </tr>
            <tfoot>
                <tr>
                    <td colspan="5"><span id="total-vencidas">Total: &nbsp;&nbsp; <?php echo formatarValor($totalVencidas); ?></span></td>
                </tr>
            </tfoot>

        </table>
        <h3>Despesas com Vencimento Hoje (<?php echo " $diaAtual/$mesAtual " ?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>Vencimento</th>
                    <th>Forma de Pgto</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody id="tbody-hoje">

                <?php if (!empty($despesasHoje)): ?>
                    <?php foreach ($despesasHoje as $despesa): ?>
                        <tr>
                            <td><?php echo formatarDataBr($despesa['data_vencimento']); ?></td>
                            <td><?php echo $despesa['forma_pagamento']; ?></td>
                            <td><?php echo htmlspecialchars($despesa['nome']); ?></td>
                            <td> <?php echo formatarValor($despesa['valor']); ?></td>

                            <td>
                                <button class="btn-marcar" data-id="<?php echo $despesa['id']; ?>" data-valor="<?php echo $despesa['valor']; ?>">Marcar como Paga</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma despesa com vencimento hoje.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tr id="msg-sem-hoje">
                <td colspan="5">Nenhuma despesa com vencimento hoje.</td>
            </tr>
            <tfoot>
                <tr>
                    <td colspan="5"><span id="total-hoje">Total: &nbsp;&nbsp; <?php echo formatarValor($totalHoje); ?></span></td>
                </tr>
            </tfoot>

        </table>
        <div class="saldo">
            <p><strong>Saldo restate do Mês Atual:</strong> <?php echo formatarValor($saldoAtual); ?></p>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>