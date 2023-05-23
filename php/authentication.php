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
        $password1 = $_POST['r_password1'];
        $password2 = $_POST['r_password2'];
        $role = $_POST['r_role'];

        userRegister($name, $email, $password1, $password2, $role);

    } else {
        echo '<a href="../home.html">Voltar</a>';
    }
}

function connectDB()
{
    require_once 'config.php';

    $servername = SERVER_NAME;
    $dbusername = DB_USERNAME;
    $dbpassword = DB_PASSWORD;
    $dbname = DB_NAME;

    $connection = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($connection->connect_error) {
        die("Falha na conexão com o banco de dados: " . $connection->connect_error);
    }

    return $connection;
}

function userLogin($email, $password) 
{
    $conn = connectDB();
    
    // Realiza a consulta para buscar o usuário no banco de dados pelo email
    $stmt = $conn->prepare("SELECT * FROM users WHERE users.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $storedPassword = $user['password'];

        // Verifica a senha usando a função password_verify()
        if (!password_verify($password, $storedPassword)) {
            // Senha incorreta
            $response = array(
                'error' => true,
                'message' => 'Senha incorreta!'
            );
        } else {
            // Sucesso no login
            $_SESSION['username'] = $user['email'];

            // Separando o primeiro nome do usuário p/ ser exibido na msg de saudação
            $partsName = explode(" ", $user['name']);
            $firstName = $partsName[0];

            $response = array(
                'error' => false,
                'message' => 'Bem-vindo(a), ' . $firstName . '!'
            );
        }

        echo json_encode($response);

        $result->free();
        $stmt->close();
        $conn->close();
        exit();

    } else {
        // Usuário não encontrado
        $response = array(
            'error' => true,
            'message' => 'Usuário não encontrado!'
        );
    }

    echo json_encode($response);
    
    $result->free();
    $stmt->close();
    $conn->close();
    exit();
}

function userRegister($name, $email, $password1, $password2, $role) 
{
    $conn = connectDB();

    if ($password1 != $password2) {
        $response = array(
            'error' => true,
            'message' => "As senhas não correspondem!"
        );

        echo json_encode($response);
        $conn->close();
        exit();
    }

    try {
        
        $stmt = $conn->prepare("INSERT INTO users (users.name, users.email, users.password, users.role)
                                VALUES (?, ?, ?, ?)");

        // Cria o hash da senha usando bcrypt antes de armazenar no bd
        $hashedPassword = password_hash($password1, PASSWORD_DEFAULT);

        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            // Usuário cadastrado com sucesso
            $response = array(
                'error' => false,
                'message' => 'Usuário cadastrado com sucesso!'
            );
        } else {
            // Erro ao cadastrar usuário
            $response = array(
                'error' => true,
                'message' => "Erro ao cadastrar usuário: " . $conn->error
            );
        }

        echo json_encode($response);
        $stmt->close();
        $conn->close();
        exit();

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // Verifica se o erro é de entrada duplicada
            $response = array(
                'error' => true,
                'message' => 'O email informado já está registrado.'
            );
        } else {
            // Outro erro ocorreu, trata de acordo com as necessidades
            $response = array(
                'error' => true,
                'message' => 'Ocorreu um erro ao realizar a operação: ' . $e->getMessage()
            );
        }
    }

    echo json_encode($response);

    $stmt->close();
    $conn->close();
    exit();
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Autenticação</title>
</head>

<body>
    <header>SIN 143 - Sistema de Cadastro de Eventos</header>
    <a href="../home.html">Voltar</a>
</body>
</html>