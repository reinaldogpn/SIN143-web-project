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
    <link rel="stylesheet" href="../assets/css/event_edit.css">
    <title>PseudoEventim - Editar evento</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h1>
        <br>
        <h2>Editar evento</h2>
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
    <div class="display_events">
        <div class="events">
            <?php
                require_once '../classes/event.php';
                $obj = new Event();
                $events = $obj->getEventsByPromoter($_SESSION['user_id']);
            ?>

            <table>
                <tr>
                    <th>Título</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Preço</th>
                    <th>Editar</th>
                </tr>

                <?php
                    if (isset($events)) 
                    {
                        foreach ($events as $event) 
                        {
                            echo '<tr>';
                            echo '<td>' . $event->getTitle() . '</td>';
                            echo '<td>' . $event->getDate() . '</td>';
                            echo '<td>' . $event->getTime() . '</td>';
                            echo '<td>' . $event->getPrice() . '</td>';
                            echo '<td><a href="event_edit.php?event_id=' . $event->getId() . '">Editar</a></td>';
                            echo '</tr>';
                        }
                    }
                    else
                    {
                        echo '<tr>';
                        echo '<td colspan="6">Nenhum evento cadastrado</td>';
                        echo '</tr>';
                    }
                ?>
        </div>
    </div>
    
    <div class="edit_event_form">
        <form action="../classes/event.php" method="POST" enctype="multipart/form-data">
            <h2>Editar evento</h2>
            <?php
                require_once '../classes/event.php';
                
                if (isset($_GET['event_id']))
                {
                    $event_id = $_GET['event_id'];
                }
                else
                {
                    $event_id = 0;
                }

                $obj = new Event();
                $event = $obj->getEventById($event_id);
            ?>
            <input type="hidden" name="event_id" value="<?= $event->getId(); ?>">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" value="<?= $event->getTitle(); ?>">
            <label for="description">Descrição</label>
            <textarea name="description" id="description" cols="30" rows="10"><?= $event->getDescription(); ?></textarea>
            <label for="date">Data</label>
            <input type="date" name="date" id="date" value="<?= $event->getDate(); ?>">
            <label for="time">Horário</label>
            <input type="time" name="time" id="time" value="<?= $event->getTime(); ?>">
            <label for="price">Preço</label>
            <input type="number" name="price" id="price" value="<?= $event->getPrice(); ?>">
            <label for="image">Imagem</label>
            <input type="file" name="image" id="image" accept="image/*">
            <br><br>
            <label for="category">Categoria</label>
            <select name="category" id="category" required>
                <option value="<?= $event->getCategory(); ?>"><?= $event->getCategory(); ?></option>
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
            <input type="submit" name="edit" id="edit" value="Salvar">
        </form>
    </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>