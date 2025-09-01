<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../controllers/controller_relatorio_individual_contribuicao.php';
require_once '../includes/functions.php';
require_once '../includes/header.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Individual de Contribuições</title>

    <link rel="stylesheet" href="../assets/css/segmentation/globals.css">
    <link rel="stylesheet" href="../assets/css/segmentation/layout-tables.css">
    <link rel="stylesheet" href="../assets/css/segmentation/tabela-relatorio-contribuicao.css">
    <link rel="stylesheet" href="../assets/css/segmentation/relatorio-contribuicao.css">

    <script src="../assets/js/utils.js" defer></script>
    <script src="../assets/js/scripts.js" defer></script>
</head>

<body class="<?= htmlspecialchars($pageClass) ?>">
    <main>
        <h2>Relatório de Dízimos/Ofertas</h2>


        <div class="container">
            <p><strong>Mês:</strong> <?= str_pad((int)$mes, 2, '0', STR_PAD_LEFT); ?> / <?= htmlspecialchars($ano); ?></p>

            <div class="tabela-relatorio-container">

                <table class="tabela-relatorio">

                    <caption>
                        <p><strong><?= htmlspecialchars($nome); ?>:</strong> <?= htmlspecialchars($descricao); ?></p>
                    </caption>
                    <thead>
                        <tr>
                            <!-- <th>Contribuição</th> -->
                            <th><?= htmlspecialchars($nomeUsuario); ?></th>
                            <?php if (!empty($dependentes)): ?>
                                <?php foreach ($dependentes as $dep): ?>
                                    <th><?= htmlspecialchars($dep); ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- <td><?= htmlspecialchars($nome); ?></td> -->

                            <?php if (!empty($dependentes)): ?>
                                <?php
                                // Divide o valor igualmente entre usuário + dependentes
                                $qtdPessoas = 1 + count($dependentes);
                                $valorIndividual = $valor / $qtdPessoas;
                                ?>
                                <td><?= formatarValor($valorIndividual); ?></td>
                                <?php foreach ($dependentes as $dep): ?>
                                    <td><?= formatarValor($valorIndividual); ?></td>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Caso não haja dependentes, mostra o valor total apenas para o usuário -->
                                <td><?= formatarValor($valor); ?></td>
                            <?php endif; ?>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="<?= 1 + (empty($dependentes) ? 1 : (1 + count($dependentes))) ?>">
                                Total de <?= htmlspecialchars($nome); ?>s: <?= formatarValor($valor); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <button title="Imprimir Relatório" class="no-print button-icon" onclick="imprimirLista()">🖨️</button>
            <button title="Fechar Relatório" class="no-print button-icon" onclick="fecharRelatorio()">❌</button>
        </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>

</html>