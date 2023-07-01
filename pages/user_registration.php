<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PseudoEventim - Cadastro</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <h2>Criar conta</h2>
    </header>
    <main class="main">
        <div class="user_register">
            <form action="../classes/user.php" method="POST">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br><br>

                <input type="submit" value="Cadastrar">
            </form>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?>
    </footer>
</body>
</html>