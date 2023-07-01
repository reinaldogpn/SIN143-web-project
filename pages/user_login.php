<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PseudoEventim - Entrar</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <h2>Entrar</h2>
    </header>
    <main class="main">
        <div class="login_div">
            <form action="../classes/authentication.php" method="POST">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br><br>

                <input type="submit" value="Entrar">
            </form>
        </div>
        <a href="user_registration.php">Criar conta</a>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?>
    </footer>
</body>
</html>