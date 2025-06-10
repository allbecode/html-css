<?php 
include 'db_connection.php';
$anoAtual = date('Y');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Rodapé</title> -->

    <link rel="stylesheet" href="assets/css/styles-principal.css">
    <link rel="stylesheet" href="assets/css/style-header.css">
    <link rel="stylesheet" href="assets/css/style_media_queries.css">
</head>

<body>
    <footer>
        <p>&copy; <?php echo $anoAtual; ?> <a href="https://www.github.com/allbecode" target="_blank">&lt;allbe<strong>code</strong>&gt;</a> - Todos os diretos
            reservados</p>
    </footer>
</body>

</html>