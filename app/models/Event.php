<?php
require_once __DIR__ . '/../config.php';

class EventModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Create a new event
    public function createEvent($name, $description, $date, $location, $capacity, $user_id, $image)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO events (name, description, date, location, capacity, created_by, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$name, $description, $date, $location, $capacity, $user_id, $image]);
    }


    // Get all events for a user
    public function getEvents($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE created_by = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get event by ID for the logged-in user
    public function getEventById($id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ? AND created_by = ?");
        $stmt->execute([$id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get event by ID for the every  user
    public function getEventByOnlyId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get paginated events with sorting and searching
    public function getPaginatedEvents($user_id, $limit, $offset, $sort, $search)
    {
        $allowedSortColumns = ['name', 'date', 'capacity'];
        $sort = in_array($sort, $allowedSortColumns) ? $sort : 'name';

        $query = "SELECT * FROM events WHERE created_by = ?";
        $params = [$user_id];

        if (!empty($search)) {
            $query .= " AND name LIKE ?";
            $params[] = "%$search%";
        }

        $query .= " ORDER BY $sort LIMIT $limit OFFSET $offset";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get total count of events with search filter
    public function getTotalEventsCount($user_id, $search)
    {
        $query = "SELECT COUNT(*) FROM events WHERE created_by = ?";
        $params = [$user_id];

        if (!empty($search)) {
            $query .= " AND name LIKE ?";
            $params[] = "%$search%";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // Update an event
    public function updateEvent($id, $name, $description, $date, $location, $capacity, $user_id, $image)
    {
        $stmt = $this->pdo->prepare("
            UPDATE events 
            SET name = ?, description = ?, date = ?, location = ?, capacity = ?, image = ? 
            WHERE id = ? AND created_by = ?
        ");
        return $stmt->execute([$name, $description, $date, $location, $capacity, $image, $id, $user_id]);
    }



    // Delete an event
    public function deleteEvent($id, $user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ? AND created_by = ?");
        return $stmt->execute([$id, $user_id]);
    }


    // public function getAllEvents()
    // {
    //     $stmt = $this->pdo->query("SELECT * FROM events ORDER BY date ASC");
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }
    public function getAllEvents($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE created_by = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getLatestEvents($limit = 5)
    {
        $limit = (int) $limit; // Ensure limit is an integer to avoid SQL errors

        $query = "SELECT * FROM events ORDER BY date ASC LIMIT " . $limit;
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchEvents($keyword)
    {
        if (empty($keyword)) {
            $stmt = $this->pdo->query("SELECT * FROM events ORDER BY date ASC");
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM events WHERE name LIKE ? OR location LIKE ? ORDER BY date ASC");
            $stmt->execute(['%' . $keyword . '%', '%' . $keyword . '%']);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
