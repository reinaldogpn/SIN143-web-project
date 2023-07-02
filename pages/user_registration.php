<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>PseudoEventim - Cadastro</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <br>
        <h2>Cadastro</h2>
        <div class="buttons">
            <?php
                session_start();
                
                if (isset($_SESSION['user_id'])) // Usuário está logado
                {
                    echo '<a href="user_logout.php">Logout</a>';

                    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') // Usuário é administrador
                    {
                        echo '<a href="dashboard.php">Painel Administrativo</a>';
                    } 
                } 
                else // Usuário não está logado
                {
                    echo '<a href="user_login.php">Login</a>';
                }
            ?>
            <a href="home.php">Página inicial</a>
        </div>
    </header>
    <main class="main">
        <div class="form_div">
            <form action="../classes/user.php" method="POST">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br>

                <select name="role" id="role">
                    <option value="user">Participante</option>
                    <option value="promoter">Organizador</option>
                </select>

                <input type="submit" value="Cadastrar">
            </form>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>