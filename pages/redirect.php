<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/events.css">
    <title>PseudoEventim - Redirecionando...</title>

    <script>
        function redirect() {
            window.location = "home.php";
        }

        setTimeout(redirect, 3000);
    </script>

    <style>
        .success-message {
        color: #34a853;
        font-weight: bold;
        }

        .error-message {
        color: #ea4335;
        font-weight: bold;
        }

        .success-message,
        .error-message {
        text-align: center;
        margin-top: 20px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h1>
        <br>
        <h2>Redirecionando...</h2>
        <div class="buttons">
            <a href="home.php">Página inicial</a>
        </div>
    </header>
    <main class="main">
        <?php
            session_start();
            
            if (isset($_SESSION['status']) && isset($_SESSION['message'])) 
            {
                $status = $_SESSION['status'];
                $message = $_SESSION['message'];
                unset($_SESSION['status']);
                unset($_SESSION['message']);
                
                if ($status == "success") 
                {
                    echo '<div class="success-message"> <h1> ' . $message . ' </h1> </div>';
                } 
                else if ($status == "error") 
                {
                    echo '<div class="error-message"> <h1> ' . $message . ' </h1> </div>';
                }
            } 
        ?>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>