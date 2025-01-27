<?php
require_once __DIR__ . '/../models/Event.php';

class EventController
{
    private $eventModel;

    public function __construct($pdo)
    {
        $this->eventModel = new EventModel($pdo);
    }

    public function createEvent($name, $description, $date, $location, $capacity)
    {
        if (empty($name) || empty($date) || empty($location) || empty($capacity)) {
            return "All fields are required.";
        }

        if (!isset($_SESSION['user_id'])) {
            return "Unauthorized access.";
        }

        $user_id = $_SESSION['user_id'];
        $image = 'default-event.jpg'; // Default image

        // Handle file upload if provided
        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';

            // Ensure directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageName = time() . '_' . basename($_FILES['event_image']['name']);
            $targetFilePath = $uploadDir . $imageName;

            // Check file type (only allow JPG, PNG)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileType = mime_content_type($_FILES['event_image']['tmp_name']);

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetFilePath)) {
                    $image = $imageName; // Save uploaded file name in database
                } else {
                    return "Failed to upload image.";
                }
            } else {
                return "Invalid image format. Only JPG and PNG are allowed.";
            }
        } else {
            // Debugging output
            if ($_FILES['event_image']['error'] === UPLOAD_ERR_INI_SIZE) {
                return "Error: Uploaded file exceeds the allowed size.";
            } elseif ($_FILES['event_image']['error'] !== UPLOAD_ERR_NO_FILE && $_FILES['event_image']['error'] !== UPLOAD_ERR_OK) {
                return "Error uploading file. Please try again.";
            }
        }


        if ($this->eventModel->createEvent($name, $description, $date, $location, $capacity, $user_id, $image)) {
            return "Event created successfully!";
        }

        return "Failed to create event.";
    }


    public function getEventsWithoutUserId()
    {
        // Allow access to events even if not logged in
        return $this->eventModel->getAllEventsWithoutUserId();
    }
    public function getEvents($user_id)
    {
        // Allow access to events even if not logged in
        return $this->eventModel->getAllEvents($user_id);
    }

    public function getEventById($id)
    {
        if (!isset($_SESSION['user_id'])) {
            return "Unauthorized access.";
        }

        $user_id = $_SESSION['user_id'];
        return $this->eventModel->getEventById($id, $user_id);
    }
    public function getEventByOnlyId($id)
    {

        return $this->eventModel->getEventByOnlyId($id);
    }

    public function getPaginatedEvents($limit, $offset, $sort, $search)
    {
        if (!isset($_SESSION['user_id'])) {
            return "Unauthorized access.";
        }

        $user_id = $_SESSION['user_id'];
        return $this->eventModel->getPaginatedEvents($user_id, $limit, $offset, $sort, $search);
    }

    public function getTotalEventsCount($search)
    {
        if (!isset($_SESSION['user_id'])) {
            return "Unauthorized access.";
        }

        $user_id = $_SESSION['user_id'];
        return $this->eventModel->getTotalEventsCount($user_id, $search);
    }

    public function updateEvent($id, $name, $description, $date, $location, $capacity)
    {
        if (!isset($_SESSION['user_id'])) {
            return "Unauthorized access.";
        }

        $user_id = $_SESSION['user_id'];
        $event = $this->eventModel->getEventById($id, $user_id);
        if (!$event) {
            return "Event not found.";
        }

        $image = $event['image'];

        if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $fileType = mime_content_type($_FILES['event_image']['tmp_name']);
            $maxFileSize = 5242880; // 5MB limit

            if ($_FILES['event_image']['size'] > $maxFileSize) {
                return "Error: File size exceeds the 5MB limit.";
            }

            if (!in_array($fileType, $allowedTypes)) {
                return "Error: Invalid image format. Only JPG, JPEG, and PNG are allowed.";
            }

            $imageName = time() . '_' . basename($_FILES['event_image']['name']);
            $targetFilePath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetFilePath)) {
                if (!empty($event['image']) && $event['image'] !== 'default-event.jpg') {
                    unlink($uploadDir . $event['image']);
                }
                $image = $imageName;
            } else {
                return "Error: Failed to upload image.";
            }
        }

        if ($this->eventModel->updateEvent($id, $name, $description, $date, $location, $capacity, $user_id, $image)) {
            return "Event updated successfully!";
        }

        return "Error updating event.";
    }





    public function deleteEvent($id)
    {
        if (!isset($_SESSION['user_id'])) {
            return "Unauthorized access.";
        }

        $user_id = $_SESSION['user_id'];
        if ($this->eventModel->deleteEvent($id, $user_id)) {
            return "Event deleted successfully!";
        }

        return "Failed to delete event.";
    }

    public function getLatestEvents($limit = 5)
    {
        return $this->eventModel->getLatestEvents($limit);
    }

    public function searchEvents($keyword)
    {
        return $this->eventModel->searchEvents($keyword);
    }
}
