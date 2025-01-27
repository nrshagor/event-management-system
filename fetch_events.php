<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/EventController.php';
require_once __DIR__ . '/app/controllers/AttendeeController.php';

header('Content-Type: application/json');

$eventController = new EventController($pdo);
$attendeeController = new AttendeeController($pdo);
$events = $eventController->getEventsWithoutUserId();

$eventList = [];

foreach ($events as $event) {
    $attendeeCount = $attendeeController->countAttendees($event['id']);
    $remainingSeats = max(0, $event['capacity'] - $attendeeCount);

    $eventList[] = [
        'id'        => $event['id'],
        'title'     => htmlspecialchars($event['name']),
        'start'     => $event['date'],  // Ensure proper date format YYYY-MM-DD
        'capacity'  => $event['capacity'],
        'attendees' => $attendeeCount,
        'remaining' => $remainingSeats,
        'description' => htmlspecialchars($event['description']),
        'location'   => htmlspecialchars($event['location']),
        'image_url'  => BASE_URL . "public/uploads/" . $event['image']
    ];
}

echo json_encode($eventList);
