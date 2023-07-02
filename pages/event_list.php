<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/event_list.css">
    <title>PseudoEventim - Busca</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <br>
        <h2>Busca</h2>
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
        <div class="search_bar_div">
            <form action="event_search.php" class="search_bar" method="GET">
                <input type="text" name="search" id="search" placeholder="Pesquisar eventos">
                <input type="submit" value="Pesquisar">
            </form>
        </div>
        <div class="events_display">
            <?php
                require_once '../classes/event.php';
                
                isset($_GET['search']) ? $search = $_GET['search'] : $search = null;

                $obj = new Event();
                $events = $obj->getEvents($search);
                if ($events != null) 
                {
                    foreach ($events as $event) 
                    {
                        echo '<div class="event">';
                        echo '<a href="event_details.php?id=' . $event->getId() . '">';
                        echo '<h3>' . $event->getTitle() . '</h3>';
                        echo '<p>' . 'Categoria: ' . $event->getCategory() . '</p>';
                        echo '</a>';
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