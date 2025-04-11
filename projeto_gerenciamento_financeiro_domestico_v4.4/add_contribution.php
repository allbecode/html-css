<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'] ?? '';
    $dataVencimento = "$ano-$mes-01";
    
    // Se for Dízimo, calcular 10% das receitas
    if ($tipo === 'dizimo') {
        $sqlReceitas = "SELECT SUM(valor) as totalReceitas FROM transacoes WHERE tipo = 'receita' AND mes = :mes AND ano = :ano";
        $stmtReceitas = $pdo->prepare($sqlReceitas);
        $stmtReceitas->bindParam(':mes', $mes);
        $stmtReceitas->bindParam(':ano', $ano);
        $stmtReceitas->execute();
        $resultadoReceitas = $stmtReceitas->fetch(PDO::FETCH_ASSOC);
        $totalReceitas = $resultadoReceitas['totalReceitas'] ?? 0;
        
        $nome = 'Dízimo';
        $valor = $totalReceitas * 0.10;

    } 
    
    // Se for Oferta, pegar o valor do input e validar
    elseif ($tipo === 'oferta') {
        $valor = floatval($_POST['valor']);

        if ($valor <= 0) {
            echo "<script>alert('Valor da oferta inválido!'); window.history.back();</script>";
            exit;
        }

        // Verificar se o total de ofertas já cadastradas ultrapassa 10% das receitas
        $sqlReceitas = "SELECT SUM(valor) as totalReceitas FROM transacoes WHERE tipo = 'receita' AND mes = :mes AND ano = :ano";
        $stmtReceitas = $pdo->prepare($sqlReceitas);
        $stmtReceitas->bindParam(':mes', $mes);
        $stmtReceitas->bindParam(':ano', $ano);
        $stmtReceitas->execute();
        $resultadoReceitas = $stmtReceitas->fetch(PDO::FETCH_ASSOC);
        $totalReceitas = $resultadoReceitas['totalReceitas'] ?? 0;

        $sqlOfertas = "SELECT SUM(valor) as totalOfertas FROM transacoes WHERE nome = 'Oferta' AND mes = :mes AND ano = :ano";
        $stmtOfertas = $pdo->prepare($sqlOfertas);
        $stmtOfertas->bindParam(':mes', $mes);
        $stmtOfertas->bindParam(':ano', $ano);
        $stmtOfertas->execute();
        $resultadoOfertas = $stmtOfertas->fetch(PDO::FETCH_ASSOC);
        $totalOfertasCadastradas = $resultadoOfertas['totalOfertas'] ?? 0;

        if (($totalOfertasCadastradas + $valor) > ($totalReceitas * 0.10)) {
            echo "<script>alert('O limite de 10% das receitas para ofertas já foi atingido!'); window.location.href='index.php';</script>";
            exit;
        }

        $nome = 'Oferta';
    } else {
        die("Erro: Tipo de contribuição inválido.");
    }

   

    // Inserir a contribuição no banco de dados
    $sql = "INSERT INTO transacoes (nome, data_vencimento, valor, tipo, forma_pagamento, mes, ano, pago, descricao) 
            VALUES (:nome, :data_vencimento, :valor, 'despesa', 'PIX', :mes, :ano, 0, :descricao)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':data_vencimento',$dataVencimento);
    $stmt->bindParam(':valor', $valor);
    $stmt->bindParam(':mes', $mes);
    $stmt->bindParam(':ano', $ano);
    $stmt->bindParam(':descricao', $descricao);

    if ($stmt->execute()) {
        echo "<script>alert('$nome adicionado como despesa!'); window.location.href='dizimos.php';</script>";

        // Redireciona para o relatório

        header("Location: relatorio_dizimo.php?mes=$mes&ano=$ano&valor_dizimo=$valor&nome=$nome");
        exit();
        
    } else {
        echo "Erro ao adicionar $nome.";
    }
}
?>
