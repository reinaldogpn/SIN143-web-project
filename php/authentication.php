<?php
// Authentication: Para gerenciar a autenticação e autorização de usuários, incluindo login e registro.

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST["action"] === "login") {

        $email = trim($_POST['l_email']);
        $password = $_POST['l_password'];

        userLogin($email, $password);

    } elseif ($_POST["action"] === "register") {
        
        $name = $_POST['r_name'];
        $email = trim($_POST['r_email']);
        $password = $_POST['r_password'];
        $role = $_POST['r_role'];

        userRegister($name, $email, $password, $role);

    } elseif ($_POST["action"] === "update") {
        
        


        userUpdate();
    }
}

function userLogin($email, $password) 
{
    require_once 'config.php';

    $servername = SERVER_NAME;
    $dbusername = DB_USERNAME;
    $dbpassword = DB_PASSWORD;
    $dbname = DB_NAME;
    
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
    
    $wrongEmail_msg = sprintf('<script>alert("%s");</script>', "Usuário não encontrado!");
    $wrongPass_msg = sprintf('<script>alert("%s");</script>', "Senha incorreta!");
    
    // Realiza a consulta para buscar o usuário no banco de dados pelo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE users.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
    
        $email = $result->fetch_assoc();
        $storedPassword = $email['password'];

        // Verifica a senha usando a função password_verify()
        if (!password_verify($pass, $storedPassword)) {
            echo $wrongPass_msg;

        }
    
    } else {
        // Usuário não encontrado
        echo $wrongEmail_msg;

    }
    
    $result->free();
    $stmt->close();
    $conn->close();
}

function userRegister($name, $email, $password, $role) 
{
    require_once 'config.php';

    $servername = SERVER_NAME;
    $dbusername = DB_USERNAME;
    $dbpassword = DB_PASSWORD;
    $dbname = DB_NAME;
    
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    $success_msg = sprintf('<script>alert("%s");</script>', "Usuário cadastrado com sucesso!");
    $error_msg = sprintf('<script>alert("%s");</script>', "Erro ao cadastrar usuário: " . $conn->error);

    try {
        
        $stmt = $conn->prepare("INSERT INTO users (users.name, users.email, users.password, users.role)
                                VALUES (?, ?, ?, ?)");

        // Cria o hash da senha usando bcrypt antes de armazenar no bd
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            // Usuário cadastrado com sucesso
            echo $success_msg;

    
        } else {
            // Erro ao cadastrar usuário
            echo $error_msg;

        }

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // Verifica se o erro é de entrada duplicada
            echo sprintf('<script>alert("%s");</script>', "O email informado já está registrado.");

        } else {
            // Outro erro ocorreu, trata de acordo com as necessidades
            echo sprintf('<script>alert("%s");</script>', "Ocorreu um erro ao realizar a operação: " . $e->getMessage());

        }
    }

    $stmt->close();
    $conn->close();
}

function userUpdate() 
{
    
}

?>