<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Gestão de Alunos</title>
</head>

<body>
    <div class="center">
        <h1>Cadastro de Alunos</h1>
        <form action="adicionar.php" method="post">

            <div>
                <label for="nome">Nome do Aluno:</label>
                <input type="text" name="nome" id="nome" required>
            </div>

            <div class="left">
                <label for="nota1">Digita a nota do 1&ordm; semestre</label>
                <input type="number" name="nota1" id="nota1" step="0.01" min="0" max="10" required>
            </div>

            <div class="right">
                <label for="nota2">Digita a nota do 2&ordm; semestre</label>
                <input type="number" name="nota2" id="nota2" step="0.01" min="0" max="10" required>
            </div>
            <br>
            <div class="left">
                <label for="nota3">Digita a nota do 3&ordm; semestre</label>
                <input type="number" name="nota3" id="nota3" step="0.01" min="0" max="10" required>
            </div>

            <div class="right">
                <label for="nota4">Digita a nota do 4&ordm; semestre</label>
                <input type="number" name="nota4" id="nota4" step="0.01" min="0" max="10" required>
            </div>
            <button type="submit">Adicionar Aluno</button>
        </form>
    </div><!-- center -->
    <h2>Lista de Alunos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>1&ordm; sem</th>
            <th>2&ordm; sem</th>
            <th>3&ordm; sem</th>
            <th>4&ordm; sem</th>
            <th>Média</th>
            <th>Ações</th>
        </tr>

        <?php
        include 'db.php';
        $query = "SELECT * FROM alunos";
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nome']}</td>
                    <td>{$row['nota1']}</td>
                    <td>{$row['nota2']}</td>
                    <td>{$row['nota3']}</td>
                    <td>{$row['nota4']}</td>
                    <td>{$row['media']}</td>
                    <td>
                        <a href='editar.php?id={$row['id']}'>Editar</a> |
                        <a href='deletar.php?id={$row['id']}' onclick='return confirm(\"Tem certeza que deseja excluir?\");'>Excluir</a>
                    </td>
                </tr>";
        }
        ?>
    </table>
</body>

</html>