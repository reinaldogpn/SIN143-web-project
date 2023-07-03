<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>PseudoEventim - Entrar</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <br>
        <h2>Entrar</h2>
        <div class="buttons">
            <a href="home.php">Página inicial</a>
        </div>
    </header>
    <main class="main">
        <div class="form_div">
            <form id="login-form" action="../classes/authentication.php" method="POST">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br>

                <input type="submit" value="Entrar">
            </form>
        </div>
        <div class="register">
            <p>Não tem uma conta? <a href="user_registration.php" class="register">Cadastre-se</a></p>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>