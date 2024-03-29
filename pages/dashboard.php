<?php

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') 
{
    header('Location: error.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/events.css">
    <title>PseudoEventim - Dashboard</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <br>
        <h2>Dashboard</h2>
        <div class="buttons">
            <?php                
                if (isset($_SESSION['user_id'])) // Usuário está logado
                {
                    if ($_SESSION['user_type'] == 'promoter' || $_SESSION['user_type'] == 'admin') // Usuário é organizador ou administrador
                    {
                        echo '<a href="event_edit.php">Editar evento</a>';
                        echo '<a href="event_create.php">Cadastrar evento</a>';
                    }

                    if ($_SESSION['user_type'] == 'admin') // Usuário é administrador
                    {
                        echo '<a href="dashboard.php">Painel Administrativo</a>';
                    } 

                    echo '<a href="user_logout.php">Logout</a>';
                } 
                else // Usuário não está logado
                {
                    echo '<a href="user_login.php">Login</a>';
                }
            ?>
        </div>
    </header>
    <main class="main">
        <div class="form_div">
                
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>