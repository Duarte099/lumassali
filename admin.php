<?php 
    session_start();

    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit();
    }

    include('./db/conexao.php');

    $sql = "SELECT * FROM administrator WHERE id = " . $_SESSION['id'] . " AND pass = '" . $_SESSION['password'] . "';";
    $result = $con->query($sql);
    if ($result->num_rows == 0) {
        header('Location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../css/style.css">
    <title>Lumassali | Admin</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <form action="processar_excel.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="importarExcel.php" accept=".xlsx,.xls" required>
        <button type="submit">Enviar</button>
    </form>
    <form action="adminInserir.php" method=post>
        <div class="container">
            <?php 
                for ($i=1; $i <= 19; $i++) { 
                    $sql = "SELECT COUNT(nomeProduto) as numProdutos FROM secoes_produtos WHERE orderSecao = $i;";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $numProdutos = $row['numProdutos'];
                    }
                    $sql = "SELECT nomeSecao FROM secoes_produtos WHERE orderSecao = $i LIMIT 1;";
                    $result = $con->query($sql);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<div class=\"category\">";
                        echo "<h2>". $row['nomeSecao'] . "</h2>";
                        for ($j=1; $j <= $numProdutos; $j++) { 
                            $sql2 = "SELECT nomeproduto, precoProduto FROM secoes_produtos WHERE orderSecao = $i AND orderProduto = $j;";
                            $result2 = $con->query($sql2);
                            if ($result2->num_rows > 0) {
                                $row2 = $result2->fetch_assoc();
                                echo "<div class=\"item\"><span>" . $row2['nomeproduto'] . "</span><input name=\"secao_" . $i . "_produto_" . $j . "\" type=\"text\" value=\"" . $row2['precoProduto'] . "\"></div>";
                            }
                        }
                        echo "</div>";
                    }
                }
            ?>
        </div>
        <button type="submit">Guardar Alterações</button>
    </form>
</body>
</html>