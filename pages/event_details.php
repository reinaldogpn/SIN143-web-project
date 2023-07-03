<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/event_det.css">
    <title>PseudoEventim - Evento</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h2>
        <br>
        <h2>Detalhes sobre o evento</h2>
        <div class="buttons">
            <?php
                if (session_status() != PHP_SESSION_ACTIVE) {
                    session_start();
                }
                
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
        <div class="event_display">
            <?php

                require_once '../classes/event.php';
                $id = $_GET['id'];
                $obj = new Event();
                $event = $obj->getEventById($id);

                if ($event != null) 
                {
                    echo '<div class="event">';
                    echo '<img src="' . $event->getImage() . '" alt="' . $event->getImage() . '">';
                    echo '<h3>' . $event->getTitle() . '</h3>';
                    echo '<p>' . 'Categoria: ' . $event->getCategory() . '</p>';
                    echo '<p>' . $event->getDescription() . '</p>';
                    echo '<p>' . 'Avaliação: ' . $event->getAVGRating() . '</p>';
                    echo '<p>R$ ' . $event->getPrice() . '</p>';
                    echo '<p>' . $event->getLocation() . '</p>';
                    echo '<p>' . date('d/m/Y', strtotime($event->getDate())) . ' - ' . date('H:i', strtotime($event->getTime())) . '</p>';
                    echo '</div>';
                } 
                else 
                {
                    echo '<p>Nenhum evento encontrado.</p>';
                }
            ?>
        </div>
        <div class="event_buy">
            <form action="../classes/registration.php" method="post">
                <input type="hidden" name="event_id" id="event_id" value="<?= $event->getId(); ?>">
                <input type="number" name="amount" id="amount" min="1" value="1" placeholder="Quantidade" oninput="updateValue()">
                <input type="number" name="value" id="value" value="<?= $event->getPrice(); ?>" readonly>
                <input type="submit" value="Comprar">
            </form>
            <script>
                function updateValue() {
                    var amount = document.getElementById("amount").value;
                    var price = <?= $event->getPrice(); ?>;
                    var value = amount * price;
                    document.getElementById("value").value = value.toFixed(2);
                }
            </script>
        </div>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>