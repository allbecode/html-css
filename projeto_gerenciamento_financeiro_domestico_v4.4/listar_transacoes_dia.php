<?php
include 'db_connection.php';

$dataHoje = date('Y-m-d');
$stmt = $pdo->prepare("SELECT * FROM transacoes WHERE data_registro = :hoje ORDER BY id DESC");
$stmt->execute(['hoje' => $dataHoje]);
$transacoes = $stmt->fetchAll();

if (count($transacoes) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <!-- <th>Tipo</th> -->
                <th>Nome</th>
                <th>Valor</th>
                <!-- <th>Forma Pagamento</th> -->
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transacoes as $t): ?>
                <tr class="transacoes-do-dia">
                    <td>
                        <?php echo date('d/m/Y', strtotime($t['data_vencimento'])); ?>
                    </td>
                    <!-- <td><?= htmlspecialchars($t['tipo']) ?></td> -->
                    <td><?= htmlspecialchars($t['nome']) ?></td>
                    <td>R$ <?= number_format($t['valor'], 2, ',', '.') ?></td>
                    <!-- <td><?= htmlspecialchars($t['forma_pagamento']) ?></td> -->
                    <td><?= htmlspecialchars($t['descricao']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Nenhuma transação registrada hoje.</p>
<?php endif; ?>
