<?php
require_once 'db.php';
require_once 'functions.php';

function registrarOperacao(string $acao, string $autor, string $alvo = ''): void {
    $pdo = conectar();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'N/A';

    $stmt = $pdo->prepare("INSERT INTO logs_acesso (username, acao, alvo, ip, data_hora) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$autor, strtoupper($acao), $alvo, $ip]);

    // Formato da chamada: 
    // registrarOperacao('acao_realizada', $usuario_logado, $usuario_alvo);

}

function renderizarTabelaLogs(array $logs): void {
    if (count($logs) === 0) {
        echo "<p>Nenhuma operação registrada.</p>";
        return;
    }

    echo '<table>
        <thead>
            <tr>
                <th>AUTOR</th><th>IP</th><th>AÇÃO</th><th>ALVO</th><th>DATA/HORA</th>
            </tr>
        </thead>';

    foreach ($logs as $log) {
        $autor = htmlspecialchars($log['username'] ?? 'N/A');
        $ip = htmlspecialchars($log['ip'] ?? 'N/A');
        $acao = htmlspecialchars($log['acao'] ?? 'N/A');
        $alvo = htmlspecialchars($log['alvo'] ?? ''); // agora evita erro com null
        $data = isset($log['data_hora']) ? formataDataPtBr($log['data_hora']) : 'N/A';
        echo '<tbody>';
        echo '<tr>';
        echo "<td>$autor</td><td>$ip</td><td>$acao</td><td>$alvo</td><td>$data</td>";
        echo '</tr>';
        echo '</tbody>';
    }

    echo '</table>';
}


function buscarUltimosLogs($username, $limite = 10) {
    $pdo = conectar();
    $stmt = $pdo->prepare("SELECT acao, ip, data_hora FROM logs_acesso WHERE username = ? ORDER BY data_hora DESC LIMIT ?");
    $stmt->bindParam(1, $username);
    $stmt->bindParam(2, $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

