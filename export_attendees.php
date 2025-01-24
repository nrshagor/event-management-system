<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/AttendeeController.php';

if (!isset($_SESSION['user_id'])) {
    redirect('public/login.php');
}

// Check if event_id is provided in the request
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    die("Invalid request. Event ID is required.");
}

// Check user role safely to avoid undefined array key error
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    die("Access denied. You must be an admin or super admin to download reports.");
}

$event_id = intval($_GET['event_id']);
$attendeeController = new AttendeeController($pdo);

// Fetch attendees with no pagination, and no search filter
$attendees = $attendeeController->listAttendees($event_id, 100000, 0, '');

// Check if there are attendees for the event
if (empty($attendees)) {
    die("No attendees found for this event.");
}

// Set CSV headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="attendees_event_' . $event_id . '.csv"');

// Open output stream to write CSV
$output = fopen('php://output', 'w');

// Add CSV column headers
fputcsv($output, ['Name', 'Email', 'Registered At']);

// Loop through attendees and write to CSV
foreach ($attendees as $attendee) {
    fputcsv($output, [
        $attendee['user_name'],
        $attendee['email'],
        $attendee['registered_at']
    ]);
}

// Close the output stream
fclose($output);
exit;
