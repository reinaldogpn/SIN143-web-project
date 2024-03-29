<?php

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $authentication = new Authentication($email, $password);
    $authentication->authenticate();
}

class Authentication
{
    private $email;
    private $password;
    private $role;
    private $connection;

    public function __construct($email, $password, $role = 'user')
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->connection = connectdb();
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    // métodos get:

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    // métodos set:

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    // métodos de autenticação:

    public function authenticate()
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
        {
            $user = $result->fetch_assoc();
            $storedPassword = $user['password'];

            if (password_verify($this->getPassword(), $storedPassword))
            {
                // Separando o primeiro nome do usuário p/ ser exibido na msg de saudação
                $partsName = explode(" ", $user['name']);

                // Inicia a sessão e armazena os dados do usuário logado
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $partsName[0];
                $_SESSION['user_type'] = $user['role'];

                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Bem-vindo(a), ' . $_SESSION['user_name'] . '!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Senha incorreta!';
                header("Location: ../pages/redirect.php");
            }
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Usuário não encontrado!';
            header("Location: ../pages/redirect.php");
        }

        $stmt->close();
    }
}

?>