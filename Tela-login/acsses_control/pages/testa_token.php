<?php
require_once '../includes/db.php';
require_once '../includes/functions.php'; // onde está a função validarToken()
require_once '../includes/recovery_passwd_functions.php';

function validarTokenDebug($pdo, $token)
{
    $stmt = $pdo->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacao = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "❌ Nenhum usuário encontrado com esse token.<br>";
        return false;
    }

    echo "🔍 ID do usuário: {$usuario['id']}<br>";
    echo "📅 Token expira em: {$usuario['token_expira']}<br>";
    echo "⏳ Data/hora atual: " . date('Y-m-d H:i:s') . "<br>";
    echo "⏲️ Timestamp token_expira: " . strtotime($usuario['token_expira']) . "<br>";
    echo "⏲️ Timestamp agora: " . time() . "<br><br>";
    echo date_default_timezone_get();
    echo "\n";
    echo date('Y-m-d H:i:s');
    echo "<br><br>";

    if (strtotime($usuario['token_expira']) > time()) {
        echo "✅ Token válido!<br>";
        return $usuario['id'];
    } else {
        echo "❌ Token expirado.<br>";
        return false;
    }
}

// ======== TESTE ========
$tokenParaTestar = '???'; // cole aqui o token do banco
validarTokenDebug($pdo, $tokenParaTestar);
