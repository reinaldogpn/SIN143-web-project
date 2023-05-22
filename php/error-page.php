<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="">
    <title>Erro</title>
</head>
<body>
    <h1>Ocorreu um erro</h1>
    <div class="error-msg-div">
        <?php

        $error_msg = $_GET['message'];
        echo "<p>" . $error_msg . "</p>";

        ?>
    </div>
    <br>
    <a href="../home.html">Página inicial</a>
</body>
</html>
