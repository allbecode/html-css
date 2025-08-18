<?php
require_once 'db.php';


function getEstatisticasGerais(PDO $pdo)
{
    return [
        'total_usuarios' => $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn(),
        'total_admins' => $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'admin'")->fetchColumn(),
        'total_logins_hoje' => $pdo->query("SELECT COUNT(*) FROM logs_acesso WHERE acao = 'login' AND DATE(data_hora) = CURDATE()")->fetchColumn()
    ];
}

function getUltimosUsuarios(PDO $pdo, $limite = 5)
{
    $stmt = $pdo->prepare("SELECT username, email, criado_em FROM usuarios ORDER BY criado_em ASC LIMIT ?");
    $stmt->bindValue(1, (int)$limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUltimosLoginsFiltrados(PDO $pdo, $filtros = [], $limite = 10)
{
    $condicoes = [];
    $parametros = [];

    if (!empty($filtros['data'])) {
        $condicoes[] = "DATE(data_hora) = ?";
        $parametros[] = $filtros['data'];
    }

    if (!empty($filtros['usuario'])) {
        $condicoes[] = "username LIKE ?";
        $parametros[] = '%' . $filtros['usuario'] . '%';
    }

    if (!empty($filtros['ip'])) {
        $condicoes[] = "ip LIKE ?";
        $parametros[] = '%' . $filtros['ip'] . '%';
    }

    if (!empty($filtros['acao'])) {
        $condicoes[] = "acao = ?";
        $parametros[] = $filtros['acao'];
    }

    if (!empty($filtros['alvo'])) {
        $condicoes[] = "alvo = ?";
        $parametros[] = $filtros['alvo'];
    }

    $sql = "SELECT username, ip, acao, alvo, data_hora FROM logs_acesso";
    if (!empty($condicoes)) {
        $sql .= " WHERE " . implode(" AND ", $condicoes);
    }

    $sql .= " ORDER BY data_hora DESC LIMIT ?";
    $parametros[] = (int)$limite;

    $stmt = $pdo->prepare($sql);
    foreach ($parametros as $i => $param) {
        $stmt->bindValue($i + 1, $param, is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR);
    }
    $stmt->execute();

    return $stmt->fetchAll();
}
