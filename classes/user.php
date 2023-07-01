<?php

require_once '../database/connection.php';

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
    
    function __construct($id, $name, $email, $password, $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
    
    // métodos get:

    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
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

    public function setName($name)
    {
        $this->name = $name;
    }
    
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
    
    // outros métodos:

    public static function createUser()
    {
        $conn = dbconnect();
        $userExists = User::getUserByEmail($this->email);

        if (!$userExists)
        {
            $stmt = $conn->prepare("INSERT INTO users (users.name, users.email, users.password, users.role) VALUES (?, ?, ?, ?)");

            // Cria o hash da senha usando bcrypt antes de armazenar no bd
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            $stmt->bind_param("ssss", $this->name, $this->email, $hashedPassword, $this->role);
            $stmt->execute();

            if ($stmt->affected_rows > 0) // Usuário cadastrado com sucesso
            {
                $response = array('error' => false, 'message' => 'Usuário cadastrado com sucesso!');
            }
            else
            {
                $response = array('error' => true, 'message' => 'Ocorreu um erro ao realizar a operação!');
            }
        }
        else
        {
            $response = array('error' => true, 'message' => 'O email informado já está registrado.');
        }

        echo json_encode($response);

        $stmt->close();
        $conn->close();
    }

    public function updateUser()
    {
        $conn = dbconnect();
        $userExists = User::getUserByEmail($this->email);

        if (!$userExists) 
        {
            $response = array('error' => true, 'message' => 'O email informado já está registrado.');
            echo json_encode($response);
            return;
        }

        // Inicializa um array para armazenar os campos e os valores a serem atualizados
        $updateFields = array();
        $types = '';
        $params = array();

        // Verifica quais campos foram fornecidos e adiciona-os ao array $updateFields
        if (!empty($this->name)) {
            $updateFields[] = 'name = ?';
            $types .= 's';
            $params[] = &$this->name;
        }
        if (!empty($this->email)) {
            $updateFields[] = 'email = ?';
            $types .= 's';
            $params[] = &$this->email;
        }
        if (!empty($this->role)) {
            $updateFields[] = 'role = ?';
            $types .= 's';
            $params[] = &$this->role;
        }
        if (!empty($this->password)) {
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $updateFields[] = 'password = ?';
            $types .= 's';
            $params[] = &$hashedPassword;
        }

        // Verifica se há campos para atualizar
        if (count($updateFields) === 0) {
            $response = array('error' => true, 'message' => 'Nenhum campo foi fornecido para atualização.');
            echo json_encode($response);
            return;
        }

        // Constrói a parte da consulta SQL com base nos campos fornecidos
        $updateFieldsString = implode(', ', $updateFields);

        // Prepara a instrução SQL dinamicamente
        $stmt = $conn->prepare("UPDATE users SET $updateFieldsString WHERE id = ?");
        $types .= 'i';
        $params[] = &$this->id;

        // Faz o bind dos parâmetros dinamicamente
        $bindParams = array_merge(array($types), $params);
        $bindParamsReferences = array();
        foreach ($bindParams as $key => $value) {
            $bindParamsReferences[$key] = &$bindParams[$key];
        }
        call_user_func_array(array($stmt, 'bind_param'), $bindParamsReferences);

        // Executa a instrução SQL
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response = array('error' => false, 'message' => 'Usuário atualizado com sucesso!');
        } else {
            $response = array('error' => true, 'message' => 'Ocorreu um erro ao atualizar o usuário.');
        }

        echo json_encode($response);

        $stmt->close();
        $conn->close();
    }

    public static function deleteUser()
    {

    }

    public static function getUserByEmail($email)
    {
        $conn = dbconnect();

        $stmt = $conn->prepare("SELECT * FROM users WHERE users.email = ?");
        $stmt->bind_param("s", $email);
        $result = $stmt->execute();

        if ($result->num_rows > 0) 
        {
            $user = $result->fetch_assoc();
            new User($user['id'], $user['name'], $user['email'], $user['password'], $user['role']);
        } 
        else 
        {
            $user = null;
        }

        $stmt->close();
        $conn->close();

        return $user;
    }
}

?>