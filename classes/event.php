<?php

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create']))
{
    // Verifica se um arquivo foi enviado
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) 
    {
        $fileName = $_FILES['image']['name'];
        $tempPath = $_FILES['image']['tmp_name'];
        $imgPath = '../assets/' . $fileName;

        // Move o arquivo do local temporário para o destino final
        if (move_uploaded_file($tempPath, $imgPath)) 
        {
            $eventImg = $imgPath;
        } 
        else 
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Ocorreu um erro ao fazer o upload do arquivo.';
            header("Location: ../pages/redirect.php");
            return;
        }
    }
    else
    {
        $eventImg = '../assets/default-img.jpeg';
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $eventImg;

    $event = new Event($title, $description, $date, $time, $location, $category, $price, $image);

    $event->createEvent($_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit']))
{
    // Verifica se um arquivo foi enviado
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) 
    {
        $fileName = $_FILES['image']['name'];
        $tempPath = $_FILES['image']['tmp_name'];
        $imgPath = '../assets/' . $fileName;

        // Move o arquivo do local temporário para o destino final
        if (move_uploaded_file($tempPath, $imgPath)) 
        {
            $eventImg = $imgPath;
        } 
        else 
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Ocorreu um erro ao fazer o upload do arquivo.';
            header("Location: ../pages/redirect.php");
            return;
        }
    }
    else
    {
        $eventImg = '../assets/default-img.jpeg';
    }

    $id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $eventImg;

    $event = new Event($title, $description, $date, $time, $location, $category, $price, $image);

    $event->updateEvent($id);
}

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
    private $created_by;
    private $connection;
    
    public function __construct($title = null, $description = null, $date = null, $time = null, $location = null, $category = null, $price = null, $image = '../assets/default-img.jpeg', $created_by = null)
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
        $this->created_by = $created_by;
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

    public function getCreatedBy()
    {
        return $this->created_by;
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

    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
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
                $event->setCreatedBy($rel_event['created_by']);
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
                $event->setCreatedBy($rel_event['created_by']);
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
            $event->setCreatedBy($rel_event['created_by']);
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
            $event->setCreatedBy($rel_event['created_by']);
        } 
        else 
        {
            $event = null;
        }

        $stmt->close();
        return $event;
    }

    public function getEventsByUserId($userId)
    {        
        $stmt = $this->connection->prepare("SELECT * FROM events JOIN registrations ON events.id = registrations.event_id JOIN users ON registrations.user_id = users.id 
                                            WHERE users.id = ? ORDER BY events.date ASC");
                                            
        $stmt->bind_param("i", $userId);
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
                $event->setCreatedBy($rel_event['created_by']);
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

    public function getEventsByPromoter($promoterId)
    {
        $stmt = $this->connection->prepare("SELECT * FROM events WHERE created_by = ?");
        $stmt->bind_param("i", $promoterId);
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
                $event->setCreatedBy($rel_event['created_by']);
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
                $event->setCreatedBy($rel_event['created_by']);
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

    public function createEvent($id)
    {
        # Verifica se o evento já existe no banco de dados
        $existingEvent = $this->getEventByTitle($this->title);

        if (!$existingEvent)
        {
            $stmt = $this->connection->prepare("INSERT INTO events (title, description, date, time, location, category, price, image, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssisi", $this->title, $this->description, $this->date, $this->time, $this->location, $this->category, $this->price, $this->image, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) // Evento cadastrado com sucesso
            {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Evento cadastrado com sucesso!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Ocorreu um erro ao realizar a operação!';
                header("Location: ../pages/redirect.php");
            }

            $stmt->close();
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Já existe um evento cadastrado com o título informado!';
            header("Location: ../pages/redirect.php");
        }
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
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Evento deletado com sucesso!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Ocorreu um erro ao realizar a operação!';
                header("Location: ../pages/redirect.php");
            }

            $stmt->close();
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Não existe um evento cadastrado com o ID informado!';
            header("Location: ../pages/redirect.php");
        }
    }

    public function updateEvent($id)
    {
        $existingEvent = $this->getEventById($id);

        if ($existingEvent)
        {
            $stmt = $this->connection->prepare("UPDATE events SET title = ?, description = ?, date = ?, time = ?, location = ?, category = ?, price = ?, image = ? WHERE id = ?");
            $stmt->bind_param("ssssssisi", $this->title, $this->description, $this->date, $this->time, $this->location, $this->category, $this->price, $this->image, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) // Evento atualizado com sucesso
            {
                $_SESSION['status'] = 'success';
                $_SESSION['message'] = 'Evento atualizado com sucesso!';
                header("Location: ../pages/redirect.php");
            }
            else
            {
                $_SESSION['status'] = 'error';
                $_SESSION['message'] = 'Ocorreu um erro ao realizar a operação!';
                header("Location: ../pages/redirect.php");
            }

            $stmt->close();
        }
        else
        {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Não existe um evento cadastrado com o ID informado!';
            header("Location: ../pages/redirect.php");
        }
    }
}

?>