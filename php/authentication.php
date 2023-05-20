<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require_once 'config.php';

    $servername = SERVER_NAME;
    $dbusername = DB_USERNAME;
    $dbpassword = DB_PASSWORD;
    $dbname = DB_NAME;

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $wrongUser = sprintf('<script>alert("%s");</script>', "Usuário não encontrado!");
    $wrongPass = sprintf('<script>alert("%s");</script>', "Senha incorreta!");

    // Realiza a consulta para buscar o usuário no banco de dados pelo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $email = $result->fetch_assoc();
        $storedPassword = $email['password'];

        // Verifica a senha usando a função password_verify()
        if (!password_verify($pass, $storedPassword)) {
            echo $wrongPass;
            echo '<meta http-equiv="refresh" content="0;URL=index.php">';
        }

    } else {
        // Usuário não encontrado
        echo $wrongUser;
        echo '<meta http-equiv="refresh" content="0;URL=index.php">';
    }

    $result->free();
    $stmt->close();
    $conn->close();
}
?>