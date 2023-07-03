<?php

session_start();

if (!isset($_SESSION['logged_in'])) 
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
    <title>PseudoEventim - Perfil</title>
</head>
<body>
    <header class="header">
        <h1>PseudoEventim</h1>
        <br>
        <h2>Perfil do Usuário</h2>
        <div class="buttons">
            <?php                
                if (isset($_SESSION['user_id'])) // Usuário está logado
                {
                    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') // Usuário é administrador
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
        <div class="container">
            <div class="display_profile"> <!-- Página de perfil do usuário, mostrando informações pessoais e histórico de eventos. -->
                <?php
                    require_once '../classes/user.php';
                    $userObj = new User();
                    $user = $userObj->getUserById($_SESSION['user_id']);
                ?>
                <h3>Informações Pessoais</h3>
                <form id="profile_form" action="../classes/user.php" method="POST" enctype="multipart/form-data">
                    <br>
                    <img src="<?php echo $user->getAvatar(); ?>" alt="user_avatar" width="222" height="222"> <!-- Imagem de perfil padrão, por enquanto. -->
                    <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;">
                    <p><strong>Nome: </strong><input type="text" name="name" value="<?php echo $user->getName(); ?>" readonly></p>
                    <p><strong>CPF: </strong><input type="text" name="cpf" value="<?php echo $user->getCpf(); ?>" readonly></p>
                    <p><strong>Telefone: </strong><input type="text" name="phone" value="<?php echo $user->getPhone(); ?>" readonly></p>
                    <p><strong>Endereço: </strong><input type="text" name="address" value="<?php echo $user->getAddress(); ?>" readonly></p>
                    <p><strong>E-mail: </strong><input type="text" name="email" value="<?php echo $user->getEmail(); ?>" readonly></p>
                    <p><strong>Senha: </strong><input type="password" name="password" value="<?php echo $user->getPassword(); ?>" readonly></p>
                    <input type="hidden" name="user_id" value="<?php echo $user->getId(); ?>">
                    <input type="button" name="user_edit" id="user_edit" value="Editar perfil">
                    <input type="submit" name="user_update" id="user_update" value="Salvar" style="display: none;">
                </form>
                <script>
                    document.getElementById('user_edit').addEventListener('click', function() {
                        var form = document.getElementById('profile_form');
                        var inputs = form.getElementsByTagName('input');
                        for (var i = 0; i < inputs.length; i++) {
                            inputs[i].readOnly = false;
                        }
                        document.getElementById('avatar').style.display = 'inline-block';
                        document.getElementById('user_edit').style.display = 'none';
                        document.getElementById('user_update').style.display = 'inline-block';
                    });
                </script>
            </div>
            <br>
            <div class="display_eventsHistory">
                <?php
                    require_once '../classes/event.php';

                    echo '<h3>Histórico de Eventos</h3>';

                    echo '<br>';

                    $eventObj = new Event();
                    $events = $eventObj->getEventsByUserId($_SESSION['user_id']);

                    if (!isset($events)) // Usuário não possui eventos cadastrados
                    {
                        echo '<p>Você ainda não possui eventos cadastrados.</p>';
                    }
                    else // Usuário possui eventos cadastrados
                    {
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Título</th>';
                        echo '<th>Data</th>';
                        echo '<th>Hora</th>';
                        echo '<th>Local</th>';
                        echo '<th>Preço</th>';
                        echo '<th>Descrição</th>';
                        echo '</tr>';

                        foreach ($events as $event) // Mostra os eventos cadastrados pelo usuário
                        {
                            echo '<tr>';
                            echo '<td>' . $event->getTitle() . '</td>';
                            echo '<td>' . $event->getDate() . '</td>';
                            echo '<td>' . $event->getTime() . '</td>';
                            echo '<td>' . $event->getLocation() . '</td>';
                            echo '<td>' . $event->getPrice() . '</td>';
                            echo '<td>' . $event->getDescription() . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    }
                ?>
            </div>
            <br>
            <div class="display_reviewsHistory">
                <?php
                    require_once '../classes/review.php';

                    echo '<h3>Histórico de Avaliações</h3>';

                    echo '<br>';

                    $reviewObj = new Review();
                    $reviews = $reviewObj->getReviewsByUserId($_SESSION['user_id']);

                    if (!isset($reviews)) // Usuário não possui avaliações cadastradas
                    {
                        echo '<p>Você ainda não possui avaliações cadastradas.</p>';
                    }
                    else // Usuário possui avaliações cadastradas
                    {
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>Evento</th>';
                        echo '<th>Nota</th>';
                        echo '<th>Comentário</th>';
                        echo '</tr>';

                        foreach ($reviews as $review) // Mostra as avaliações cadastradas pelo usuário
                        {
                            echo '<tr>';
                            echo '<td>' . $review->getEventId() . '</td>';
                            echo '<td>' . $review->getRating() . '</td>';
                            echo '<td>' . $review->getComment() . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    }
                ?>
            </div>
        </div>
        <br>
    </main>
    <footer class="footer">
        Sistema de Gerenciamento de Eventos: PseudoEventim <?= date ('Y'); ?> - Laboratório de Programação (SIN 143)
    </footer>
</body>
</html>