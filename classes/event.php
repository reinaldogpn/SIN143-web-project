<?php

require_once __DIR__ . '/../database/connection.php';

class Event
{
    private $id;
    private $title;
    private $description;
    private $date;
    private $time;
    private $location;
    private $category;
    private $price;
    private $image;
    private $avg_rating;
    private $created_at;
    private $updated_at;
    private $connection;
    
    public function __construct($title = null, $description = null, $date = null, $time = null, $location = null, $category = null, $price = null, $image = '../assets/default.jpeg')
    {
        $this->id = null;
        $this->title = $title;
        $this->description = $description;
        $this->date = $date;
        $this->time = $time;
        $this->location = $location;
        $this->category = $category;
        $this->price = $price;
        $this->image = $image;
        $this->avg_rating = 0;
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getAVGRating()
    {
        return $this->avg_rating;
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

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function setAVGRating($avg_rating)
    {
        $this->avg_rating = $avg_rating;
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

    public function getEvents($term = '', $category = null) // buscar eventos por título ou local, filtrando por categoria se for o caso
    {

        if ($category == null) 
        {
            $stmt = $this->connection->prepare("SELECT * FROM events WHERE title LIKE ? OR location LIKE ? ORDER BY created_at DESC");
            $term = '%' . $term . '%';
            $stmt->bind_param("ss", $term, $term);
        } 
        else 
        {
            $stmt = $this->connection->prepare("SELECT * FROM events WHERE (title LIKE ? OR location LIKE ?) AND category = ? ORDER BY created_at DESC");
            $term = '%' . $term . '%';
            $stmt->bind_param("sss", $term, $term, $category);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $events = array();

            while ($rel_event = $result->fetch_assoc()) 
            {
                $event = new Event($rel_event['title'], $rel_event['description'], $rel_event['date'], $rel_event['time'], $rel_event['location'], $rel_event['category'], $rel_event['price'], $rel_event['image']);
                $event->setId($rel_event['id']);
                $event->setAVGRating($rel_event['avg_rating']);
                $event->setCreatedAt($rel_event['created_at']);
                $event->setUpdatedAt($rel_event['updated_at']);
                array_push($events, $event);
            }
        } 
        else 
        {
            $events = null;
        }

        $stmt->close();
        return $events;
    }

    public function getNewestEvents()
    {
        $stmt = $this->connection->prepare("SELECT * FROM events ORDER BY created_at DESC LIMIT 3");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $events = array();

            while ($rel_event = $result->fetch_assoc()) 
            {
                $event = new Event($rel_event['title'], $rel_event['description'], $rel_event['date'], $rel_event['time'], $rel_event['location'], $rel_event['category'], $rel_event['price'], $rel_event['image']);
                $event->setId($rel_event['id']);
                $event->setAVGRating($rel_event['avg_rating']);
                $event->setCreatedAt($rel_event['created_at']);
                $event->setUpdatedAt($rel_event['updated_at']);
                array_push($events, $event);
            }
        } 
        else 
        {
            $events = null;
        }

        $stmt->close();
        return $events;
    }

    public function getEventById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $rel_event = $result->fetch_assoc();
            $event = new Event($rel_event['title'], $rel_event['description'], $rel_event['date'], $rel_event['time'], $rel_event['location'], $rel_event['category'], $rel_event['price'], $rel_event['image']);
            $event->setId($rel_event['id']);
            $event->setAVGRating($rel_event['avg_rating']);
            $event->setCreatedAt($rel_event['created_at']);
            $event->setUpdatedAt($rel_event['updated_at']);
        } 
        else 
        {
            $event = null;
        }

        $stmt->close();
        return $event;
    }

    public function getEventByTitle($title)
    {
        $stmt = $this->connection->prepare("SELECT * FROM events WHERE title = ?");
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $rel_event = $result->fetch_assoc();
            $event = new Event($rel_event['title'], $rel_event['description'], $rel_event['date'], $rel_event['time'], $rel_event['location'], $rel_event['category'], $rel_event['price'], $rel_event['image']);
            $event->setId($rel_event['id']);
            $event->setAVGRating($rel_event['avg_rating']);
            $event->setCreatedAt($rel_event['created_at']);
            $event->setUpdatedAt($rel_event['updated_at']);
        } 
        else 
        {
            $event = null;
        }

        $stmt->close();
        return $event;
    }

    public function getEventsByCategory($category)
    {
        $stmt = $this->connection->prepare("SELECT * FROM events WHERE category = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $events = array();

            while ($rel_event = $result->fetch_assoc()) 
            {
                $event = new Event($rel_event['title'], $rel_event['description'], $rel_event['date'], $rel_event['time'], $rel_event['location'], $rel_event['category'], $rel_event['price'], $rel_event['image']);
                $event->setId($rel_event['id']);
                $event->setAVGRating($rel_event['avg_rating']);
                $event->setCreatedAt($rel_event['created_at']);
                $event->setUpdatedAt($rel_event['updated_at']);
                array_push($events, $event);
            }
        } 
        else 
        {
            $events = null;
        }

        $stmt->close();
        return $events;
    }

    public function getCategories()
    {
        $stmt = $this->connection->prepare("SELECT DISTINCT category_name FROM categories");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $categories = array();

            while ($rel_category = $result->fetch_assoc()) 
            {
                array_push($categories, $rel_category['category_name']);
            }
        } 
        else 
        {
            $categories = null;
        }

        $stmt->close();
        return $categories;
    }

    public function createEvent()
    {
        # Verifica se o evento já existe no banco de dados
        $existingEvent = $this->getEventByTitle($this->title);

        if (!$existingEvent)
        {
            $stmt = $this->connection->prepare("INSERT INTO events (title, description, date, time, location, category, price, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssis", $this->title, $this->description, $this->date, $this->time, $this->location, $this->category, $this->price, $this->image);
            $stmt->execute();

            if ($stmt->affected_rows > 0) // Evento cadastrado com sucesso
            {
                $response = array('error' => false, 'message' => 'Evento cadastrado com sucesso!');
            }
            else
            {
                $response = array('error' => true, 'message' => 'Ocorreu um erro ao realizar a operação!');
            }

            $stmt->close();
        }
        else
        {
            $response = array('error' => true, 'message' => 'Já existe um evento cadastrado com o título informado!');
        }

        echo json_encode($response);
    }

    public function deleteEvent($id)
    {
        $existingEvent = $this->getEventById($id);

        if ($existingEvent)
        {
            $stmt = $this->connection->prepare("DELETE FROM events WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) // Evento deletado com sucesso
            {
                $response = array('error' => false, 'message' => 'Evento deletado com sucesso!');
            }
            else
            {
                $response = array('error' => true, 'message' => 'Ocorreu um erro ao realizar a operação!');
            }

            $stmt->close();
        }
        else
        {
            $response = array('error' => true, 'message' => 'Não existe um evento cadastrado com o ID informado!');
        }

        echo json_encode($response);
    }

    public function updateEventImage($id, $image)
    {
        $existingEvent = $this->getEventById($id);

        if ($existingEvent)
        {
            $stmt = $this->connection->prepare("UPDATE events SET image = ? WHERE id = ?");
            $stmt->bind_param("si", $image, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) // Imagem do evento atualizada com sucesso
            {
                $response = array('error' => false, 'message' => 'Imagem do evento atualizada com sucesso!');
            }
            else
            {
                $response = array('error' => true, 'message' => 'Ocorreu um erro ao realizar a operação!');
            }

            $stmt->close();
        }
        else
        {
            $response = array('error' => true, 'message' => 'Não existe um evento cadastrado com o ID informado!');
        }

        echo json_encode($response);
    }
}

?>