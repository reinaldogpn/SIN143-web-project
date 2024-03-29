<?php

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../database/connection.php';

if (isset($_SESSION['user_id']))
{
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $amount = $_POST['amount'];
    $value = $_POST['value'];

    $registration = new Registration($user_id, $event_id, $amount, $value);
    $registration->createPayment();
}
else
{
    $_SESSION['status'] = 'error';
    $_SESSION['message'] = 'Você deve estar logado(a) para fazer a compra!';
    header("Location: ../pages/redirect.php");
}

class Registration
{
    private $id;
    private $user_id;
    private $event_id;
    private $amount;
    private $value;
    private $payment_status;
    private $created_at;
    private $updated_at;
    private $connection;
    
    public function __construct($user_id, $event_id, $amount, $value, $payment_status = 0)
    {
        $this->id = null;
        $this->user_id = $user_id;
        $this->event_id = $event_id;
        $this->amount = $amount;
        $this->value = $value;
        $this->payment_status = $payment_status;
        $this->created_at = null;
        $this->updated_at = null;
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

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getEventId()
    {
        return $this->event_id;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    // métodos set:

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    // métodos de persistência:

    public function createPayment()
    {
        $stmt = $this->connection->prepare("INSERT INTO registrations (user_id, event_id, amount, value, payment_status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiii", $this->user_id, $this->event_id, $this->amount, $this->value, $this->payment_status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) 
        {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Compra realizada com sucesso!';
            header("Location: ../pages/redirect.php");
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Falha ao realizar a compra!';
            header("Location: ../pages/redirect.php");
        }

        $stmt->close();
    }

    public function updatePaymentStatus($user_id, $event_id, $payment_status)
    {
        $stmt = $this->connection->prepare("UPDATE registrations SET payment_status = ? WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("iii", $payment_status, $user_id, $event_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) 
        {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Status do pagamento atualizado com sucesso!';
            header("Location: ../pages/redirect.php");
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Falha ao atualizar o status do pagamento!';
            header("Location: ../pages/redirect.php");
        }

        $stmt->close();
    }

    public function deletePayment($user_id, $event_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("ii", $user_id, $event_id);
        $result = $stmt->execute();

        if($result)
        {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Pagamento removido com sucesso!';
            header("Location: ../pages/redirect.php");
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Falha ao remover o pagamento!';
            header("Location: ../pages/redirect.php");
        }

        $stmt->close();
    }
}

?>