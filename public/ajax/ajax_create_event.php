<?php

require_once __DIR__ . '../../../app/config.php';
require_once __DIR__ . '../../../app/controllers/EventController.php';

header('Content-Type: application/json');

// Validate and handle file upload
if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == UPLOAD_ERR_OK) {
    $maxFileSize = 5242880; // 5MB
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

    if ($_FILES['event_image']['size'] > $maxFileSize) {
        echo json_encode(["success" => false, "message" => "Error: File size exceeds 5MB limit."]);
        exit;
    }

    $fileType = mime_content_type($_FILES['event_image']['tmp_name']);
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Error: Only JPG, JPEG, and PNG files are allowed."]);
        exit;
    }
}

try {
    $eventController = new EventController($pdo);

    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $location = htmlspecialchars($_POST['location']);
    $capacity = intval($_POST['capacity']);

    if ($eventController->createEvent($name, $description, $date, $location, $capacity)) {
        $newEventId = $pdo->lastInsertId();

        // Send back the new event HTML row
        echo json_encode([
            "success" => true,
            "message" => "Event created successfully!",
            "html" => '<tr id="event-' . $newEventId . '">
                            <td>' . htmlspecialchars($name) . '</td>
                            <td>' . htmlspecialchars($description) . '</td>
                            <td>' . htmlspecialchars($date) . '</td>
                            <td>' . htmlspecialchars($location) . '</td>
                            <td>' . htmlspecialchars($capacity) . '</td>
                            <td>
                                <a href="update_event.php?id=' . $newEventId . '" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm delete-event" data-id="' . $newEventId . '">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                                <a href="register_attendee.php?event_id=' . $newEventId . '" class="btn btn-primary btn-sm">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                                <a href="view_attendees.php?event_id=' . $newEventId . '" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                       </tr>'
        ]);
        exit;
    } else {
        echo json_encode(["success" => false, "message" => "Error creating event."]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Exception: " . $e->getMessage()]);
    exit;
}
