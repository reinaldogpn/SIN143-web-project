<?php

require_once '../database/connection.php';

class Review
{
    private $id;
    private $user_id;
    private $event_id;
    private $rating;
    private $comment;
    private $created_at;
    private $connection;

    public function __construct($user_id, $event_id, $rating, $comment)
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
}

?>