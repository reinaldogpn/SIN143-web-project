<?php

require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $user = new User($name, $email, $password, $role);

    $user->createUser();
}

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $connection;
    
    public function __construct($name, $email, $password, $role = 'user')
    {
        $this->id = null;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->connection = connectdb();
        // echo "Conexão aberta!"; //debug
    }

    public function __destruct()
    {
        $this->connection->close();
        // echo "Conexão fechada!"; //debug
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

    public function getConn()
    {
        return $this->connection;
    }

    // métodos set:

    public function setId($id)
    {
        $this->id = $id;
    }

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
    
    // métodos de persistência:

    public function getUsers()
    {
        $stmt = $this->connection->prepare("SELECT id, name, email, role FROM users");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $users = array();

            while ($rel_user = $result->fetch_assoc()) 
            {
                $user = new User($rel_user['name'], $rel_user['email'], $rel_user['password'], $rel_user['role']);
                $user->setId($rel_user['id']);
                array_push($users, $user);
            }
        } 
        else 
        {
            $users = null;
        }

        $stmt->close();
        return $users;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $rel_user = $result->fetch_assoc();
            $user = new User($rel_user['name'], $rel_user['email'], $rel_user['password'], $rel_user['role']);
            $user->setId($rel_user['id']);
        } 
        else 
        {
            $user = null;
        }

        $stmt->close();
        return $user;
    }

    public function getUserById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();

        if ($result->num_rows > 0) 
        {
            $rel_user = $result->fetch_assoc();
            $user = new User($rel_user['name'], $rel_user['email'], $rel_user['password'], $rel_user['role']);
            $user->setId($rel_user['id']);
        } 
        else 
        {
            $user = null;
        }

        $stmt->close();
        return $user;
    }

    public function createUser()
    {
        $existingUser = $this->getUserByEmail($this->email);

        if (!$existingUser)
        {
            $stmt = $this->connection->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");

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

            $stmt->close();
        }
        else
        {
            $response = array('error' => true, 'message' => 'O email informado já está registrado.');
        }

        echo json_encode($response);
    }

    public function updateUser($id, $name, $email, $password, $role)
    {
        $existingUser = $this->getUserById($id);

        // Inicializa um array para armazenar os campos e os valores a serem atualizados
        $updateFields = array();
        $types = '';
        $params = array();

        if ($existingUser)
        {
            // Verifica quais campos foram fornecidos e adiciona-os ao array $updateFields
            if ($existingUser->getName() !== $name)
            {
                $updateFields[] = 'name = ?';
                $types .= 's';
                $params[] = $name;
            }
            if ($existingUser->getEmail() !== $email)
            {
                $updateFields[] = 'email = ?';
                $types .= 's';
                $params[] = $email;
            }
            if ($existingUser->getPassword() !== $password)
            {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $updateFields[] = 'password = ?';
                $types .= 's';
                $params[] = $hashedPassword;
            }
            if ($existingUser->getName() !== $role)
            {
                $updateFields[] = 'role = ?';
                $types .= 's';
                $params[] = $role;
            }

            if (count($updateFields) === 0) // Verifica se há campos para atualizar
            {
                $response = array('error' => false, 'message' => 'Nenhuma alteração foi feita.');
                echo json_encode($response);
            }
            else
            {
                // Constrói a parte da consulta SQL com base nos campos fornecidos
                $updateFieldsString = implode(', ', $updateFields);

                // Prepara a instrução SQL dinamicamente
                $stmt = $this->connection->prepare("UPDATE users SET $updateFieldsString WHERE id = ?");
                $types .= 'i';
                $params[] = $id;

                // Faz o bind dos parâmetros dinamicamente
                $bindParams = array_merge(array($types), $params);
                $bindParamsReferences = array();

                foreach ($bindParams as $key => $value) 
                {
                    $bindParamsReferences[$key] = $bindParams[$key];
                }

                call_user_func_array(array($stmt, 'bind_param'), $bindParamsReferences);

                // Executa a instrução SQL
                $stmt->execute();

                if ($stmt->affected_rows > 0) 
                {
                    $response = array('error' => false, 'message' => 'Usuário atualizado com sucesso!');
                } 
                else 
                {
                    $response = array('error' => true, 'message' => 'Ocorreu um erro ao tentar atualizar o usuário.');
                }

                $stmt->close();
                echo json_encode($response);
            }

        }
        else
        {
            $response = array('error' => true, 'message' => 'Usuário não encontrado.');
            echo json_encode($response);
        }
    }

    public function deleteUser($id)
    {
        $existingUser = $this->getUserById($id);

        if($existingUser)
        {
            $stmt = $this->connection->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();

            if($result)
            {
                $response = array('error' => false, 'message' => 'Usuário removido do sistema.');
            }
            else
            {
                $response = array('error' => true, 'message' => 'Falha ao tentar remover o usuário do sistema!');
            }

            $stmt->close();
            echo json_encode($response);
        }
        else
        {
            $response = array('error' => true, 'message' => 'Usuário não encontrado.');
            echo json_encode($response);
        }
    }
}

?>