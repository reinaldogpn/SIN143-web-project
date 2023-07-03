<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/events.css">
    <title>PseudoEventim - Home</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h1>
        <br>
        <h2>Página Inicial</h2>
        <div class="buttons">
            <?php
                if (session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) // Usuário está logado
                {
                    echo '<a href="user_profile.php">Meu perfil</a>';

                    if (isset($_SESSION['user_type']) || $_SESSION['user_type'] == 'promoter') // Usuário é organizador ou administrador
                    {
                        echo '<a href="event_list.php">Eventos</a>';

                        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') // Usuário é administrador
                        {
                            echo '<a href="dashboard.php">Painel Administrativo</a>';
                        } 
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
        <div class="search_bar_div">
            <form action="event_list.php" class="search_bar" method="GET">
                <input type="text" name="search" id="search" placeholder="Pesquisar eventos">
                <input type="submit" value="Pesquisar">
            </form>
        </div>
        <div class="content">
            <div class="events_display">
                <?php
                    require_once '../classes/event.php';
                    $obj = new Event();
                    $events = $obj->getNewestEvents();
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
                        echo '<p>Nenhum evento cadastrado.</p>';
                    }
                ?>
                <a href="event_list.php" class="see_all">Ver todos</a>
            </div>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>