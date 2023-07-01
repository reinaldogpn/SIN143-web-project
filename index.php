<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>PseudoEventim - Home</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <br>
        <h2>Página Inicial</h2>
        <div class="buttons">
            <a href="pages/user_login.php">Login</a>
        </div>
    </header>
    <main class="main">
        <div class="search_bar_div">
            <form action="classes/event.php" class="search_bar" method="GET">
                <input type="text" name="search" id="search" placeholder="Pesquisar eventos">
                <input type="submit" value="Pesquisar">
            </form>
        </div>
        <div class="events_display">
            <?php
                require_once 'classes/event.php';
                $obj = new Event();
                $events = $obj->getEvents();
                if ($events != null) 
                {
                    foreach ($events as $event) 
                    {
                        echo '<div class="event">';
                        echo '<img src="' . $event->getImage() . '" alt="' . $event->getImage() . '">';
                        echo '<h3>' . $event->getTitle() . '</h3>';
                        echo '<p>' . $event->getDescription() . '</p>';
                        echo '<p>' . date('d/m/Y', strtotime($event->getDate())) . ' - ' . date('H:i', strtotime($event->getTime())) . '</p>';
                        echo '<p>' . $event->getLocation() . '</p>';
                        echo '<p>' . 'Categoria: ' . $event->getCategory() . '</p>';
                        echo '<p>R$ ' . $event->getPrice() . '</p>';
                        echo '</div>';
                    }
                } 
                else 
                {
                    echo '<p>Nenhum evento cadastrado.</p>';
                }
            ?>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>