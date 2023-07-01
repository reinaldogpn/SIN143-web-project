<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>PseudoEventim - Home</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <h2>Página Inicial</h2>
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
                $event = new Event();
                $events = $event->getEvents();
            ?>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?>
    </footer>
</body>
</html>