<?php


require_once __DIR__ . '../../../app/config.php';
require_once __DIR__ . '../../../app/controllers/EventController.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

// Validate form inputs
if (empty($_POST['event_id']) || empty($_POST['name']) || empty($_POST['date']) || empty($_POST['location']) || empty($_POST['capacity'])) {
    echo json_encode(["success" => false, "message" => "Invalid form submission."]);
    exit;
}

$eventController = new EventController($pdo);

$eventId = intval($_POST['event_id']);
$name = htmlspecialchars($_POST['name']);
$description = htmlspecialchars($_POST['description']);
$date = $_POST['date'];
$location = htmlspecialchars($_POST['location']);
$capacity = intval($_POST['capacity']);

// Update event
$message = $eventController->updateEvent($eventId, $name, $description, $date, $location, $capacity);

if (strpos($message, 'successfully') !== false) {
    $updatedEvent = $eventController->getEventById($eventId);

    echo json_encode([
        "success" => true,
        "message" => "Event updated successfully!",
        "data" => [
            "name" => $updatedEvent['name'],
            "description" => $updatedEvent['description'],
            "date" => date('Y-m-d\TH:i', strtotime($updatedEvent['date'])),
            "location" => $updatedEvent['location'],
            "capacity" => $updatedEvent['capacity'],
        ],
        "image_url" => BASE_URL . "public/uploads/" . $updatedEvent['image']
    ]);
    exit;
} else {
    echo json_encode(["success" => false, "message" => $message]);
    exit;
}
