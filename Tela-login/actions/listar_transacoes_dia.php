<?php
// Conteúdo da lista de transações realizadas no dia no form de adição de transações.

require_once '../acsses_control/includes/db.php';
require_once '../includes/functions.php';
require_once '../acsses_control/includes/session.php';

verificaUsuarioLogado();

$dataHoje = date('Y-m-d');
$usuarioId = $_SESSION['usuario_id']; // ID do usuário logado

$stmt = $pdo->prepare("SELECT * 
FROM transacoes 
WHERE data_registro = :hoje 
AND usuario_id = :usuario_id
ORDER BY id DESC
");
$stmt->execute([
    'hoje' => $dataHoje,
    'usuario_id' => $usuarioId
]);
$transacoes = $stmt->fetchAll();

if (count($transacoes) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Nome</th>
                <th>Valor</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transacoes as $t): ?>
                <tr class="transacoes-do-dia">
                    <td>
                        <?php echo formatarDataBr($t['data_vencimento']); ?>
                    </td>
                    <td><?= htmlspecialchars($t['nome']) ?></td>
                    <td> <?php echo formatarValor($t['valor']) ?></td>
                    <td><?= htmlspecialchars($t['descricao']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <br><p>Nenhuma transação registrada hoje.</p><br>
<?php endif; ?>
