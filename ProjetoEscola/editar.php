<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $nota1 = $_POST['nota1'];
    $nota2 = $_POST['nota2'];
    $nota3 = $_POST['nota3'];
    $nota4 = $_POST['nota4'];
    $media = ($nota1 + $nota2 + $nota3 + $nota4) / 4;

    $query = "UPDATE alunos SET nome = '$nome', nota1 = $nota1, nota2 = $nota2, nota3 = $nota3, nota4 = $nota4, media = $media WHERE id = $id";

    if ($conn->query($query) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
} else {
    $id = $_GET['id'];
    $query = "SELECT * FROM alunos WHERE id = $id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
?>
    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="css/style.css">
        <title>Editar Aluno</title>
    </head>

    <body>
        <div class="center">
            <h1>Editar Aluno</h1>
            <form action="editar.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <div>
                    <label for="nome">Nome do Aluno:</label>
                    <input type="text" name="nome" value="<?php echo $row['nome']; ?>" required>
                </div>

                <div class="left">
                    <label for="nota1">Nota 1&ordm; semestre</label>
                    <input type="number" name="nota1" step="0.01" value="<?php echo $row['nota1']; ?>" required>
                </div>

                <div class="right">
                    <label for="nota2">Nota 2&ordm; semestre</label>
                    <input type="number" name="nota2" step="0.01" value="<?php echo $row['nota2']; ?>" required>
                </div>
                <br>
                <div class="left">
                    <label for="nota3">Nota 3&ordm; semestre</label>
                    <input type="number" name="nota3" step="0.01" value="<?php echo $row['nota3']; ?>" required>
                </div>

                <div class="right">
                    <label for="nota4">Nota 4&ordm; semestre</label>
                    <input type="number" name="nota4" step="0.01" value="<?php echo $row['nota4']; ?>" required>
                </div>

                <button type="submit">Atualizar</button>
            </form>
        </div><!-- center -->
    </body>

    </html>
<?php } ?>