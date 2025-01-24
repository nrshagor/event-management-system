<?php
require_once __DIR__ . '/../../app/config.php';
require_once __DIR__ . '/../../app/controllers/EventController.php';

header('Content-Type: application/json');

if (!isset($_GET['event_id'])) {
    echo json_encode(["success" => false, "message" => "Event ID is required."]);
    exit;
}

$eventId = intval($_GET['event_id']);
$eventController = new EventController($pdo);
$event = $eventController->getEventById($eventId);

if ($event) {
    echo json_encode([
        "success" => true,
        "event" => [
            "name" => $event['name'],
            "date" => $event['date'],
            "location" => $event['location'],
            "capacity" => $event['capacity'],
            "image_url" => BASE_URL . "public/uploads/" . $event['image']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Event not found."]);
}
