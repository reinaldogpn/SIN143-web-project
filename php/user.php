<?php
// User: Para gerenciar usuários e suas informações, como nome, e-mail e senha.

session_start();

if (!isset($_SESSION['username'])) {
    echo 'ERRO! Você deve estar logado para acessar essa página!';
    echo '<meta http-equiv="refresh" content="3;URL=../user-login.html">';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Executa a função apropriada com base na ação
    if ($_GET['action'] === 'getUserProfile') {
        showUserProfile();
    } else if ($_GET['action'] === 'getEventTable') {
        showEventTable();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'updateUser') {

    $name = $_POST['new_name'];
    $email = $_POST['new_email'];
    $password1 = $_POST['new_password1'];
    $password2 = $_POST['new_password2'];

    updateUser($name, $email, $password1, $password2);
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

function showUserProfile()
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT users.name, users.email, users.role FROM users WHERE users.email = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result;
    } else {
        $response = array(
            'error' => true,
            'message' => 'Perfil não encontrado!'
        );

        echo json_encode($response);
        exit();
    }
}

function showEventTable()
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT events.title, events.description, events.date, events.time, events.location, events.category, events.price, events.image 
                            FROM events JOIN registrations ON events.id = registrations.event_id JOIN users ON registrations.user_id = users.id 
                            WHERE users.email = ? GROUP BY events.date ORDER BY events.date");

    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $eventos = array();
        while ($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
        echo json_encode($eventos);
    } else {
        $response = 'NA';
        echo json_encode($response);
    }
}

function updateUser($id, $user, $email)
{
    $conn = connectDB();

    // Constrói a consulta de atualização dinamicamente, incluindo apenas os campos que possuem valores
    $query = "UPDATE users SET ";
    $params = array();

    if (!empty($user)) {
        $query .= "username = ?, ";
        $params[] = &$user;
    }

    if (!empty($email)) {
        $query .= "email = ?, ";
        $params[] = &$email;
    }

    // Remove a vírgula extra no final da consulta
    $query = rtrim($query, ", ");

    // Adiciona a cláusula WHERE para identificar o usuário a ser atualizado
    $query .= " WHERE id = ?";
    $params[] = &$id;

    // Prepara a consulta de atualização
    $stmt = $conn->prepare($query);

    // Verifica se houve erro ao preparar a consulta
    if (!$stmt) {
        die("Erro ao preparar a consulta de atualização: " . $conn->error);
    }

    // Vincula os parâmetros à consulta
    $bindParams = array_merge(array(str_repeat('s', count($params))), $params);
    call_user_func_array(array($stmt, 'bind_param'), $bindParams);

    // Executa a consulta
    if ($stmt->execute()) {
        // A atualização foi bem-sucedida
        echo "Usuário atualizado com sucesso.";
        echo '<meta http-equiv="refresh" content="3;URL=dashboard.php">';
    } else {
        // Ocorreu um erro ao atualizar o usuário
        echo "Erro ao atualizar o usuário: " . $stmt->error;
        echo '<meta http-equiv="refresh" content="3;URL=dashboard.php">';
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
}


?>