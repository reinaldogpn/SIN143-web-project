<?php

session_start();
echo 'Logout efetuado com sucesso!';
session_destroy();
header('Location: home.php');
exit();

?>