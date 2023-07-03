<?php

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
echo 'Logout efetuado com sucesso!';
session_unset();
session_destroy();

header('Location: home.php');
exit();

?>