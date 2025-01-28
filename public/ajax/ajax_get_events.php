<?php

require_once __DIR__ . '../../../app/config.php';
require_once __DIR__ . '../../../app/controllers/EventController.php';

$eventController = new EventController($pdo);
$events = $eventController->getEventsWithoutUserId();

foreach ($events as $event): ?>
    <tr id="event-<?= $event['id'] ?>">
        <td><?= htmlspecialchars($event['name']) ?></td>
        <td><?= htmlspecialchars($event['description']) ?></td>
        <td><?= htmlspecialchars($event['date']) ?></td>
        <td><?= htmlspecialchars($event['location']) ?></td>
        <td><?= htmlspecialchars($event['capacity']) ?></td>
        <td>
            <a href="update_event.php?id=<?= $event['id'] ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button class="btn btn-danger btn-sm delete-event" data-id="<?= $event['id'] ?>">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
            <a href="register_attendee.php?event_id=<?= $event['id'] ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus"></i> Register Attendees
            </a>
            <a href="view_attendees.php?event_id=<?= $event['id'] ?>" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View Attendees
            </a>
        </td>
    </tr>
<?php endforeach; ?>