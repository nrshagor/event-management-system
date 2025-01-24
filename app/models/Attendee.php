<?php
require_once __DIR__ . '/../config.php';

class AttendeeModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getEventDetails($event_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                (SELECT COUNT(*) FROM attendees WHERE event_id = ?) AS total_registered, 
                (SELECT capacity FROM events WHERE id = ?) AS capacity
        ");
        $stmt->execute([$event_id, $event_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkIfEmailExists($event_id, $email)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM attendees WHERE event_id = ? AND email = ?");
        $stmt->execute([$event_id, $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAttendee($event_id, $user_name, $email)
    {
        $stmt = $this->pdo->prepare("INSERT INTO attendees (event_id, user_name, email) VALUES (?, ?, ?)");
        return $stmt->execute([$event_id, $user_name, $email]);
    }

    public function getAttendeesByEvent($event_id, $limit, $offset, $search = '')
    {
        $query = "SELECT user_name, email, registered_at FROM attendees WHERE event_id = :event_id";

        if (!empty($search)) {
            $query .= " AND (user_name LIKE :search OR email LIKE :search)";
        }

        $query .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':event_id', $event_id, PDO::PARAM_INT);

        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAttendeeCount($event_id, $search = '')
    {
        $query = "SELECT COUNT(*) AS total FROM attendees WHERE event_id = ?";

        if (!empty($search)) {
            $query .= " AND (user_name LIKE ? OR email LIKE ?)";
        }

        $stmt = $this->pdo->prepare($query);

        if (!empty($search)) {
            $stmt->execute([$event_id, "%$search%", "%$search%"]);
        } else {
            $stmt->execute([$event_id]);
        }

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
