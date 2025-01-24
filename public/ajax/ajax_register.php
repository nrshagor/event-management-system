<?php
require_once __DIR__ . '../../../app/config.php';
require_once __DIR__ . '../../../app/controllers/AttendeeController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $user_name = htmlspecialchars($_POST['user_name']);
    $email = htmlspecialchars($_POST['email']);

    $attendeeController = new AttendeeController($pdo);
    $result = $attendeeController->registerAttendee($event_id, $user_name, $email);

    echo json_encode(['success' => $result === "Registration successful!", 'message' => $result]);
}
