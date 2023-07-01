<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>PseudoEventim - Home</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <h2>Página Inicial</h2>
    </header>
    <main class="main">
        <div class="search_bar_div">
            <form action="classes/event.php" class="search_bar" method="GET">

            </form>
        </div>
        <div class="events_display">

        </div>
        <div class="teste">
            <h2>Cadastro de Usuário</h2>
            <form action="classes/user.php" method="POST">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br><br>

                <label for="role">Função:</label>
                <input type="text" id="role" name="role" required><br><br>

                <input type="submit" value="Cadastrar">
            </form>
        </div>
    </main>
    <footer class="footer">
        Sistema de Cadastro de Eventos: PseudoEventim <?= date ('Y'); ?>
    </footer>
</body>
</html>