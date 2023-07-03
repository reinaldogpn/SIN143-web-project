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
            <form action="../classes/user.php" method="POST" enctype="multipart/form-data">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required><br>

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" required><br>

                <label for="phone">Telefone:</label>
                <input type="text" id="phone" name="phone"><br>

                <label for="address">Endereço:</label>
                <input type="text" id="address" name="address"><br>

                <lablel for="avatar">Foto de Perfil:</label>
                <input type="file" id="avatar" name="avatar" accept="image/*"><br>
                <br>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required><br>

                <label for="passwordcheck">Confirme a senha:</label>
                <input type="password" id="passwordcheck" name="passwordcheck" required><br>

                <select name="role" id="role">
                    <option value="user">Participante</option>
                    <option value="promoter">Organizador</option>
                </select>

                <input type="submit" id="create" name="create" value="Cadastrar">
            </form>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>