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

    $user = $_POST['email'];
    $pass = $_POST['password'];

    $wrongUser = sprintf('<script>alert("%s");</script>', "Usuário não encontrado!");
    $wrongPass = sprintf('<script>alert("%s");</script>', "Senha incorreta!");

    // Realiza a consulta para buscar o usuário no banco de dados
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $storedPassword = $user['password'];

        // Verifica a senha usando a função password_verify()
        if (password_verify($pass, $storedPassword)) {
            
            // Autenticação bem-sucedida, checar se o usuário é admin
            $stmt2 = $conn->prepare("SELECT role_id FROM user_roles JOIN users ON user_roles.user_id = users.id WHERE users.username = ?");
            $stmt2->bind_param("s", $user['username']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $value = $result2->fetch_assoc();

            if ($value['role_id'] === 0) {
                $_SESSION['username'] = $user['username'];
                header("Location: php/dashboard.php"); // Redireciona para a página de dashboard
            } else {
                echo $forbidden;
                echo '<meta http-equiv="refresh" content="0;URL=index.php">';
            }

            $stmt2->close();
            $result2->free();

        } else {
            // Senha incorreta
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