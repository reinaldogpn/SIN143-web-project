<?php
// Authentication: Para gerenciar a autenticação e autorização de usuários, incluindo login e registro.

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST["action"] === "register") {

        $user = $_POST['newUser'];
        $pass = $_POST['newPass'];
        $email = trim($_POST['newEmail']);
        $role = $_POST['newRole'];

        newUser($user, $pass, $email, $role);

    } elseif ($_POST["action"] === "update") {
        
        $id = $_POST['upId'];
        $user = trim($_POST['upUser']);
        $email = trim($_POST['upEmail']);

        updateUser($id, $user, $email);

    } elseif ($_POST["action"] === "delete") {
        
        $id = $_POST['delId'];

        deleteUser($id);
    }
}

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
            //echo '<meta http-equiv="refresh" content="0;URL=../html/user-login.html">';
        }

    } else {
        // Usuário não encontrado
        echo $wrongUser;
        //echo '<meta http-equiv="refresh" content="0;URL=../html/user-login.html">';
    }

    $result->free();
    $stmt->close();
    $conn->close();
}
?>