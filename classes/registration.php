<?php

require_once '../database/connection.php';

class Registration
{
    private $id;
    private $user_id;
    private $event_id;
    private $payment_status;
    private $connection;
    
    public function __construct($user_id, $event_id, $payment_status = 0)
    {
        $this->id = null;
        $this->user_id = $user_id;
        $this->event_id = $event_id;
        $this->payment_status = $payment_status;
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

    public function getPaymentStatus()
    {
        return $this->payment_status;
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

    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;
    }

    // métodos de persistência:

    public function getPayments()
    {
        $stmt = $this->connection->prepare("SELECT * FROM registrations");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $payments = array();

        if ($result->num_rows > 0)
        {
            while ($rel_payment = $result->fetch_assoc())
            {
                $payment = new Registration($rel_payment['user_id'], $rel_payment['event_id'], $rel_payment['payment_status']);
                $payment->setId($rel_payment['id']);
                array_push($payments, $payment);
            }
        }
        else
        {
            $payments = null;
        }

        $stmt->close();

        return $payments;
    }

    public function registerPayment($user_id, $event_id, $payment_status)
    {
        $stmt = $this->connection->prepare("INSERT INTO registrations (user_id, event_id, payment_status) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $event_id, $payment_status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) 
        {
            $response = array('error' => false, 'message' => 'Pagamento registrado com sucesso!');
        }
        else
        {
            $response = array('error' => true, 'message' => 'Falha ao registrar o pagamento!');
        }

        $stmt->close();
        echo json_encode($response);
    }

    public function updatePaymentStatus($user_id, $event_id, $payment_status)
    {
        $stmt = $this->connection->prepare("UPDATE registrations SET payment_status = ? WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("iii", $payment_status, $user_id, $event_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) 
        {
            $response = array('error' => false, 'message' => 'Status do pagamento atualizado com sucesso!');
        }
        else
        {
            $response = array('error' => true, 'message' => 'Falha ao atualizar o status do pagamento!');
        }

        $stmt->close();
        echo json_encode($response);
    }

    public function deletePayment($user_id, $event_id)
    {
        $stmt = $this->connection->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("ii", $user_id, $event_id);
        $result = $stmt->execute();

        if($result)
        {
            $response = array('error' => false, 'message' => 'Pagamento removido do sistema.');
        }
        else
        {
            $response = array('error' => true, 'message' => 'Falha ao tentar remover o pagamento do sistema!');
        }

        echo json_encode($response);
        $stmt->close();
    }
}

?>