<?php

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] != 'promoter' && $_SESSION['user_type'] != 'admin')) 
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
    <link rel="stylesheet" href="../assets/css/event_create.css">
    <title>PseudoEventim - Cadastrar evento</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h1>
        <br>
        <h2>Cadastrar evento</h2>
        <div class="buttons">
            <?php                
                if (isset($_SESSION['user_id'])) // Usuário está logado
                {
                    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') // Usuário é administrador
                    {
                        echo '<a href="dashboard.php">Painel Administrativo</a>';
                    }

                    echo '<a href="event_list.php">Eventos</a>';
                    echo '<a href="user_logout.php">Logout</a>';
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
    <div class="create_event_form">
        <form action="../classes/event.php" method="POST" enctype="multipart/form-data">
            <label for="name">Título do evento:</label>
            <input type="text" name="title" id="title" placeholder="Nome do evento" required>
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" cols="30" rows="10" placeholder="Descrição do evento"></textarea>
            <label for="category">Categoria:</label>
            <select name="category" id="category" required>
                <option value="0">Selecione uma categoria</option>
                <?php
                    require_once '../classes/event.php';
                    $obj = new Event();
                    $categories = $obj->getCategories();
                    if ($categories != null) 
                    {
                        foreach ($categories as $category) 
                        {
                            echo '<option value="' . $category . '">' . $category . '</option>';
                        }
                    }
                ?>
            </select>
            <label for="date">Data:</label>
            <input type="date" name="date" id="date" required>
            <label for="time">Horário:</label>
            <input type="time" name="time" id="time" required>
            <label for="location">Local:</label>
            <input type="text" name="location" id="location" placeholder="Local do evento" required>
            <label for="price">Preço:</label>
            <input type="number" name="price" id="price" step="0.01" min="0" data-type="currency" placeholder="R$">
            <label for="image">Imagem:</label>
            <input type="file" name="image" id="image">
            <input type="submit" name="create" id="create" value="Cadastrar evento">
        </form>
    </div>

    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>