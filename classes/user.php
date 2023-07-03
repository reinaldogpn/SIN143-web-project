<?php

require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) 
{
    // Verifica se um arquivo foi enviado
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) 
    {
        $fileName = $_FILES['avatar']['name'];
        $tempPath = $_FILES['avatar']['tmp_name'];
        $avatarPath = '../assets/' . $fileName;

        // Move o arquivo do local temporário para o destino final
        if (move_uploaded_file($tempPath, $avatarPath)) 
        {
            $userAvatar = $avatarPath;
        } 
        else 
        {
            $response = array('error' => true, 'message' => 'Ocorreu um erro ao fazer o upload do arquivo.');
            echo json_encode($response);
            return;
        }
    }
    else
    {
        $userAvatar = '../assets/default-avatar.png';
    }

    $name = $_POST['name'];
    $cpf = $_POST['cpf'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $avatar = $userAvatar;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $user = new User($name, $cpf, $phone, $address, $avatar, $email, $password, $role);
    $user->createUser();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_update']))
{
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) 
    {
        $fileName = $_FILES['avatar']['name'];
        $tempPath = $_FILES['avatar']['tmp_name'];
        $avatarPath = '../assets/' . $fileName;

        // Move o arquivo do local temporário para o destino final
        if (move_uploaded_file($tempPath, $avatarPath)) 
        {
            $userAvatar = $avatarPath;
        } 
        else 
        {
            $response = array('error' => true, 'message' => 'Ocorreu um erro ao fazer o upload do arquivo.');
            echo json_encode($response);
            return;
        }
    }
    else
    {
        $userAvatar = null;
    }

    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $cpf = $_POST['cpf'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $avatar = $userAvatar;
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User();
    $user->updateUser($id, $name, $cpf, $phone, $address, $avatar, $email, $password);
}

class User
{
    private $id;
    private $name;
    private $cpf;
    private $phone;
    private $address;
    private $avatar;
    private $email;
    private $password;
    private $role;
    private $connection;
    
    public function __construct($name = null, $cpf = null, $phone = null, $address = null, $avatar = null, $email = null, $password = null, $role = 'user')
    {
        $this->id = null;
        $this->name = $name;
        $this->cpf = $cpf;
        $this->phone = $phone;
        $this->address = $address;
        $this->avatar = $avatar;
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

    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getAvatar()
    {
        return $this->avatar;
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

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
                $user = new User($rel_user['name'], $rel_user['cpf'], $rel_user['phone'], $rel_user['address'], $rel_user['avatar'], $rel_user['email'], $rel_user['role']);
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
            $user = new User($rel_user['name'], $rel_user['cpf'], $rel_user['phone'], $rel_user['address'], $rel_user['avatar'], $rel_user['email'], $rel_user['password'], $rel_user['role']);
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
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $rel_user = $result->fetch_assoc();
            $user = new User($rel_user['name'], $rel_user['cpf'], $rel_user['phone'], $rel_user['address'], $rel_user['avatar'], $rel_user['email'], $rel_user['password'], $rel_user['role']);
            $user->setId($id);
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
            $stmt = $this->connection->prepare("INSERT INTO users (name, cpf, phone, address, avatar, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            // Cria o hash da senha usando bcrypt antes de armazenar no bd
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssssssss", $this->name, $this->cpf, $this->phone, $this->address, $this->avatar, $this->email, $hashedPassword, $this->role);
            $stmt->execute();

            if ($stmt->affected_rows > 0) // Usuário cadastrado com sucesso
            {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Usuário cadastrado com sucesso!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Erro ao cadastrar usuário!';
                header("Location: ../pages/redirect.php");
            }

            $stmt->close();
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'E-mail já cadastrado!';
            header("Location: ../pages/redirect.php");
        }
    }


    public function updateUser($id, $name, $cpf, $phone, $address, $avatar, $email, $password)
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
            if ($existingUser->getCpf() !== $cpf)
            {
                $updateFields[] = 'cpf = ?';
                $types .= 's';
                $params[] = $cpf;
            }
            if ($existingUser->getPhone() !== $phone)
            {
                $updateFields[] = 'phone = ?';
                $types .= 's';
                $params[] = $phone;
            }
            if ($existingUser->getAddress() !== $address)
            {
                $updateFields[] = 'address = ?';
                $types .= 's';
                $params[] = $address;
            }
            if (isset($avatar) && $existingUser->getAvatar() !== $avatar)
            {
                $updateFields[] = 'avatar = ?';
                $types .= 's';
                $params[] = $avatar;
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

            if (count($updateFields) === 0) // Verifica se há campos para atualizar
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Nenhum campo foi alterado!';
                header("Location: ../pages/redirect.php");
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

                foreach($bindParams as $key => $value)
                {
                    $bindParamsReferences[$key] = &$bindParams[$key];
                }

                call_user_func_array(array($stmt, 'bind_param'), $bindParamsReferences);

                // Executa a instrução SQL
                $stmt->execute();

                if ($stmt->affected_rows > 0) 
                {
                    $_SESSION['status'] = 'success';
                    $_SESSION['message'] = 'Usuário atualizado com sucesso!';
                    header("Location: ../pages/redirect.php");
                } 
                else 
                {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Erro ao atualizar usuário!';
                    header("Location: ../pages/redirect.php");
                }

                $stmt->close();
            }

        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Usuário não encontrado!';
            header("Location: ../pages/redirect.php");
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
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Usuário removido com sucesso!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Erro ao remover usuário!';
                header("Location: ../pages/redirect.php");
            }

            $stmt->close();
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Usuário não encontrado!';
            header("Location: ../pages/redirect.php");
        }
    }
}

?>