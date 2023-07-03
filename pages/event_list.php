<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/events.css">
    <title>PseudoEventim - Busca</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h1>
        <br>
        <h2>Busca</h2>
        <div class="buttons">
            <?php
                if (session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                
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
            <a href="home.php">Página inicial</a>
        </div>
    </header>
    <main class="main">
        <div class="search_bar_div">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search_bar" method="GET">
                <input type="text" name="search" id="search" placeholder="Pesquisar eventos">
                <select name="filter" id="filter">
                    <option value="0">Todas as categorias</option>
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
                <input type="submit" value="Pesquisar">
            </form>
        </div>
        <div class="events_display">
        <?php
            require_once '../classes/event.php';

            $obj = new Event();

            if (isset($_GET['search']) && isset($_GET['filter'])) 
            {
                if (!empty($_GET['search']) && $_GET['filter'] == 0) 
                {
                    $events = $obj->getEvents($_GET['search']);
                } 
                else if (!empty($_GET['search']) && $_GET['filter'] != 0) 
                {
                    $events = $obj->getEvents($_GET['search'], $_GET['filter']);
                } 
                else if (empty($_GET['search']) && $_GET['filter'] != 0) 
                {
                    $events = $obj->getEvents($_GET['search'], $_GET['filter']);
                } 
                else 
                {
                    $events = $obj->getEvents();
                }
            }
            else if (isset($_GET['search']) && !isset($_GET['filter'])) 
            {
                $events = $obj->getEvents($_GET['search']);
            }
            else
            {
                $events = $obj->getEvents();
            }
        
            if ($events != null) 
            {
                foreach ($events as $event) {
                    echo '<div class="modules">';
                    echo '<a href="event_details.php?id=' . $event->getId() . '">';
                    echo '<img src="' . $event->getImage() . '" height="222" width="222" alt="' . $event->getImage() . '">';
                    echo '<h3>' . $event->getTitle() . '</h3>';
                    echo '</a>';
                    echo '<p>' . $event->getDescription() . '</p>';
                    echo '<p>' . date('d/m/Y', strtotime($event->getDate())) . ' - ' . date('H:i', strtotime($event->getTime())) . '</p>';
                    echo '</div>';
                }
            } 
            else 
            {
                echo '<p>Nenhum evento encontrado.</p>';
            }
        ?>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>