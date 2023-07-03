<?php

require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Recupera os valores enviados pelo formulário
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cria uma instância da classe Authentication
    $authentication = new Authentication($email, $password);

    // Chama o método authenticate para autenticar o usuário
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
        // Realiza a consulta para buscar o usuário no banco de dados pelo email
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
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $partsName[0];
                $_SESSION['user_type'] = $user['role'];

                $response = array('error' => false, 'message' => 'Bem-vindo(a), ' . $_SESSION['user_name'] . '!');
                header('Location: ../pages/home.php');
            }
            else
            {
                $response = array('error' => true, 'message' => 'Senha incorreta!');
            }
        }
        else
        {
            $response = array('error' => true, 'message' => 'Usuário não encontrado!');
        }

        $stmt->close();
        echo json_encode($response);
    }
}

?>