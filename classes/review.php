<?php

require_once __DIR__ . '/../database/connection.php';

class Review
{
    private $id;
    private $user_id;
    private $event_id;
    private $rating;
    private $comment;
    private $created_at;
    private $connection;

    public function __construct($user_id = null, $event_id = null, $rating = null, $comment = null)
    {
        $this->id = null;
        $this->user_id = $user_id;
        $this->event_id = $event_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = null;
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

    public function getRating()
    {
        return $this->rating;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
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

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    // métodos de persistência:

    public function getReviews()
    {
        $stmt = $this->connection->prepare("SELECT * FROM reviews");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
        {
            $reviews = array();

            while ($rel_review = $result->fetch_assoc())
            {
                $review = new Review($rel_review['user_id'], $rel_review['event_id'], $rel_review['rating'], $rel_review['comment']);
                $review->setId($rel_review['id']);
                $review->setCreatedAt($rel_review['created_at']);
                array_push($reviews, $review);
            }
        }
        else
        {
            $reviews = null;
        }

        $stmt->close();

        return $reviews;
    }

    public function getReviewById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
        {
            $rel_review = $result->fetch_assoc();
            $review = new Review($rel_review['user_id'], $rel_review['event_id'], $rel_review['rating'], $rel_review['comment']);
            $review->setId($rel_review['id']);
            $review->setCreatedAt($rel_review['created_at']);
        }
        else
        {
            $review = null;
        }

        $stmt->close();

        return $review;
    }

    public function getReviewsByUserId($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
        {
            $reviews = array();

            while ($rel_review = $result->fetch_assoc())
            {
                $review = new Review($rel_review['user_id'], $rel_review['event_id'], $rel_review['rating'], $rel_review['comment']);
                $review->setId($rel_review['id']);
                $review->setCreatedAt($rel_review['created_at']);
                array_push($reviews, $review);
            }
        }
        else
        {
            $reviews = null;
        }

        $stmt->close();

        return $reviews;
    }

    public function createReview()
    {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("ii", $this->user_id, $this->event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Você já avaliou este evento!';
            header("Location: ../pages/redirect.php");
        }
        else
        {
            if ($this->rating < 0 || $this->rating > 10)
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Avaliação inválida! Deve ser um número entre 0 e 10!';
                header("Location: ../pages/redirect.php");
            }
            else if (strlen($this->comment) > 500)
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Comentário inválido! Deve ter no máximo 500 caracteres!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $stmt2 = $this->connection->prepare("INSERT INTO reviews (user_id, event_id, rating, comment) VALUES (?, ?, ?, ?)");
                $stmt2->bind_param("iiis", $this->user_id, $this->event_id, $this->rating, $this->comment);
                $stmt2->execute();

                if ($stmt2->affected_rows > 0)
                {
                    $_SESSION['status'] = 'success';
                    $_SESSION['message'] = 'Avaliação registrada com sucesso!';
                    header("Location: ../pages/redirect.php");
                }
                else
                {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Erro ao registrar avaliação!';
                    header("Location: ../pages/redirect.php");
                }

                $stmt2->close();
            }
        }

        $stmt->close();
    }

    public function updateReview($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0)
        {
            if ($this->rating < 0 || $this->rating > 10)
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Avaliação inválida! Deve ser um número entre 0 e 10!';
                header("Location: ../pages/redirect.php");
            }
            else if (strlen($this->comment) > 500)
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Comentário inválido! Deve ter no máximo 500 caracteres!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $stmt2 = $this->connection->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
                $stmt2->bind_param("isi", $this->rating, $this->comment, $id);
                $stmt2->execute();

                if ($stmt2->affected_rows > 0)
                {
                    $_SESSION['status'] = 'success';
                    $_SESSION['message'] = 'Avaliação atualizada com sucesso!';
                    header("Location: ../pages/redirect.php");
                }
                else
                {
                    $_SESSION['status'] = 'error';
                    $_SESSION['message'] = 'Erro ao atualizar avaliação!';
                    header("Location: ../pages/redirect.php");
                }

                $stmt2->close();
            }
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Avaliação não encontrada!';
            header("Location: ../pages/redirect.php");
        }

        $stmt->close();
    }

    public function deleteReview($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0)
        {
            $_SESSION['status'] = 'success';
            $_SESSION['message'] = 'Avaliação excluída com sucesso!';
            header("Location: ../pages/redirect.php");
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Erro ao excluir avaliação!';
            header("Location: ../pages/redirect.php");
        }

        $stmt->close();
    }
}

?>