<?php

/*
function conectar(): PDO {
    $dsn = 'mysql:host=localhost;dbname=master;charset=utf8mb4';
    return new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}
*/

/*
function conectar() {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $bancoPadrao = 'financeiro'; // caso precise usar o banco mestre em algum lugar

    // Se o banco do usuário estiver definido na sessão, usa ele
    if (isset($_SESSION['usuario_banco'])) {
        $dbname = $_SESSION['usuario_banco'];
    } else {
        // Caso contrário, conecta ao banco padrão
        $dbname = $bancoPadrao;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}
*/

/*
function conectarBancoDoUsuario() {
    if (!isset($_SESSION['id']) || !isset($_SESSION['usuario'])) {
        die("Usuário não autenticado.");
    }

    // Ignorar conexão se for o usuário administrador (ID 1)
    if ($_SESSION['id'] == 1) {
        return null;
    }

    $host = 'localhost';
    $usuario = 'root';
    $senha = '';

    $id = $_SESSION['id'];
    $username = strtolower(preg_replace('/[^a-z0-9_]/i', '_', $_SESSION['usuario']));
    $bancoUsuario = "financeiro_{$id}_{$username}";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$bancoUsuario;charset=utf8mb4", $usuario, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro ao conectar ao banco do usuário: " . $e->getMessage());
    }
}*/



/*
function criarBancoUsuario($id, $username) {
    $usernameSanitizado = preg_replace('/[^a-z0-9_]/i', '_', strtolower($username));
    $nomeBanco = "financeiro_{$id}_{$usernameSanitizado}";

    try {
        // Conexão ao MySQL (sem selecionar um banco ainda)
        $pdo = new PDO('mysql:host=localhost', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cria o banco
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$nomeBanco` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

        // Conecta ao banco recém criado
        $pdoNovo = new PDO("mysql:host=localhost;dbname=$nomeBanco", 'root', '');
        $pdoNovo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cria a tabela transacoes
        $sql = "
        CREATE TABLE IF NOT EXISTS transacoes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            data_vencimento DATE NOT NULL,
            valor DECIMAL(10,2) NOT NULL,
            tipo ENUM('receita', 'despesa') NOT NULL,
            ano INT(11) NOT NULL,
            mes INT(11) NOT NULL,
            pago BOOLEAN DEFAULT 0,
            forma_pagamento ENUM('Boleto Bancário','Cartão de Crédito','Cheque','Crédito em Conta','Débito em Conta','Débito Automático','Espécie','PIX') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PIX',
            descricao VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            data_registro DATE DEFAULT (CURRENT_DATE),
            base_contribuicao BOOLEAN NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        $pdoNovo->exec($sql);

        return $nomeBanco;
    } catch (PDOException $e) {
        error_log("Erro ao criar banco/tabela do usuário: " . $e->getMessage());
        return false;
    }
}
*/

/*
function excluirBancoUsuario($id, $username) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';

    // Montar nome do banco com segurança
    $nomeBanco = 'financeiro_' . intval($id) . '_' . strtolower(preg_replace('/[^a-z0-9_]/i', '_', $username));

    try {
        // Conexão sem banco selecionado
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Excluir banco de dados
        $pdo->exec("DROP DATABASE IF EXISTS `$nomeBanco`");

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao excluir banco do usuário: " . $e->getMessage());
        return false;
    }
}
*/


function conectar(): PDO {
    $dsn = 'mysql:host=localhost;dbname=master;charset=utf8mb4';
    return new PDO($dsn, 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}

/*
function criarBancoUsuario($id, $username) {
    $usernameSanitizado = preg_replace('/[^a-z0-9_]/i', '_', strtolower($username));
    $nomeBanco = "financeiro_{$id}_{$usernameSanitizado}";

    try {
        // Conexão ao MySQL (sem selecionar um banco ainda)
        $pdo = new PDO('mysql:host=localhost', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cria o banco
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$nomeBanco` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

        // Conecta ao banco recém criado
        $pdoNovo = new PDO("mysql:host=localhost;dbname=$nomeBanco", 'root', '');
        $pdoNovo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cria a tabela transacoes
        $sql = "
        CREATE TABLE IF NOT EXISTS transacoes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            data_vencimento DATE NOT NULL,
            valor DECIMAL(10,2) NOT NULL,
            tipo ENUM('receita', 'despesa') NOT NULL,
            ano INT(11) NOT NULL,
            mes INT(11) NOT NULL,
            pago BOOLEAN DEFAULT 0,
            forma_pagamento ENUM('Boleto Bancário','Cartão de Crédito','Cheque','Crédito em Conta','Débito em Conta','Débito Automático','Espécie','PIX') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PIX',
            descricao VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            data_registro DATE DEFAULT (CURRENT_DATE),
            base_contribuicao BOOLEAN NOT NULL DEFAULT 0,
            usuario_id INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        $pdoNovo->exec($sql);

        return $nomeBanco;
    } catch (PDOException $e) {
        error_log("Erro ao criar banco/tabela do usuário: " . $e->getMessage());
        return false;
    }
}
*/
/*
function excluirBancoUsuario($id, $username) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';

    // Montar nome do banco com segurança
    $nomeBanco = 'financeiro_' . intval($id) . '_' . strtolower(preg_replace('/[^a-z0-9_]/i', '_', $username));

    try {
        // Conexão sem banco selecionado
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Excluir banco de dados
        $pdo->exec("DROP DATABASE IF EXISTS `$nomeBanco`");

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao excluir banco do usuário: " . $e->getMessage());
        return false;
    }
}
*/

// Conexão global com o banco master
try {
    $pdo = conectar();
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>
