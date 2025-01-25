<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/EventController.php';
require_once __DIR__ . '/app/controllers/AttendeeController.php';

header('Content-Type: application/json');

if (!isset($_GET['event_id'])) {
    echo json_encode(["success" => false, "message" => "Event ID is required."]);
    exit;
}

$eventId = intval($_GET['event_id']);
$eventController = new EventController($pdo);
$attendeeController = new AttendeeController($pdo);
$event = $eventController->getEventByOnlyId($eventId);
$attendeeCount = $attendeeController->countAttendees($eventId);

if ($event) {
    $eventDate = new DateTime($event['date'], new DateTimeZone('UTC'));
    $currentDate = new DateTime('now', new DateTimeZone('UTC'));

    $eventClosed = $eventDate < $currentDate;

    echo json_encode([
        "success" => true,
        "event" => [
            "name" => $event['name'],
            "date" => date('F j, Y, g:i A', strtotime($event['date'])),
            "location" => $event['location'],
            "description" => $event['description'],
            "capacity" => $event['capacity'],
            "attendees" => $attendeeCount,
            "remaining" => max(0, $event['capacity'] - $attendeeCount),
            "image_url" => BASE_URL . "public/uploads/" . $event['image'],
            "closed" => $eventClosed
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Event not found."]);
}
