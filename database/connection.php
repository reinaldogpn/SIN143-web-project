<?php

function connectdb() 
{
    $servername = 'localhost';
    $dbusername = 'root';
    $dbpassword = '';
    $dbname = 'pseudoeventim';

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }
    
    return $conn;
}

?>
