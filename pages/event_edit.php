<?php

session_start();

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
    <link rel="stylesheet" href="../assets/css/profile.css">
    <title>PseudoEventim - Editar evento</title>
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
    <!-- Create event: Página para editar um evento que o organizador tenha criado -->

    <div class="display_events">
        <h2>Eventos cadastrados</h2>
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
                    <th>Excluir</th>
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
                            echo '<td><a href="../classes/event.php?event_id=' . $event->getId() . '&delete=true">Excluir</a></td>';
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
            <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" value="<?php echo $event->getTitle(); ?>" required>
            <label for="description">Descrição</label>
            <textarea name="description" id="description" cols="30" rows="10" required><?php echo $event->getDescription(); ?></textarea>
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
            <label for="date">Data</label>
            <input type="date" name="date" id="date" value="<?php echo $event->getDate(); ?>" required>
            <label for="time">Horário</label>
            <input type="time" name="time" id="time" value="<?php echo $event->getTime(); ?>" required>
            <label for="location">Local:</label>
            <input type="text" name="location" id="location" value="<?php echo $event->getLocation(); ?>" required>
            <label for="price">Preço</label>
            <input type="number" name="price" id="price" value="<?php echo $event->getPrice(); ?>" required>
            <label for="image">Imagem</label>
            <input type="file" name="image" id="image">
            <input type="submit" name="edit" value="Editar evento">
        </form>
    </div>

    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>