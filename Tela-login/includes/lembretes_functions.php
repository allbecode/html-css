<?php

function formatarLembrete(string $data, string $tipoNome): string {
    $hoje = new DateTime();
    $proxima = new DateTime($data);

    if ($proxima < $hoje) {
        $dias = $hoje->diff($proxima)->days;
        return "O serviço <strong>{$tipoNome}</strong> está vencido há {$dias} dias.";
    }

    if ($proxima <= (clone $hoje)->modify('+30 days')) {
        $dias = $hoje->diff($proxima)->days;
        return "O serviço <strong>{$tipoNome}</strong> vence em {$dias} dias.";
    }

    return "";
}
