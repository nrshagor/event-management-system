<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/EventController.php';
require_once __DIR__ . '/app/controllers/AttendeeController.php';

$eventController = new EventController($pdo);
$attendeeController = new AttendeeController($pdo);

// Get the latest 5 events for homepage
$events = $eventController->getLatestEvents(5);

if (!is_array($events)) {
    $events = [];
}
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container text-center mt-5">
    <h1 class="text-primary font-weight-bold">Welcome to Event Management</h1>
    <p class="lead text-muted">Discover and register for upcoming events with ease!</p>

    <div class="row mt-4">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event):
                $attendeeCount = $attendeeController->countAttendees($event['id']);
                $remainingSeats = max(0, $event['capacity'] - $attendeeCount);
            ?>
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 mb-4">
                        <img src="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($event['image']) ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($event['name']) ?>"
                            style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h3 class="card-title text-primary"><?= htmlspecialchars($event['name']) ?></h3>
                            <p class="card-text text-muted"><?= htmlspecialchars($event['description']) ?></p>
                            <p><strong>Date:</strong> <?= date('F j, Y, g:i A', strtotime($event['date'])) ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: <?= ($attendeeCount / $event['capacity']) * 100 ?>%;"
                                    aria-valuenow="<?= $attendeeCount ?>" aria-valuemin="0" aria-valuemax="<?= $event['capacity'] ?>">
                                    <?= $attendeeCount ?> / <?= $event['capacity'] ?> Registered
                                </div>
                            </div>
                            <?php if ($remainingSeats > 0): ?>
                                <a href="<?= BASE_URL ?>public/register_attendee.php?event_id=<?= $event['id'] ?>" class="btn btn-primary btn-block">
                                    Register Now (<?= $remainingSeats ?> left)
                                </a>
                            <?php else: ?>
                                <button class="btn btn-danger btn-block" disabled>
                                    Event is fully booked
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">No upcoming events at the moment. Stay tuned!</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- See More Events Button -->
    <a href="<?= BASE_URL ?>search_results.php" class="btn btn-outline-primary btn-lg mt-3">
        See All Events
    </a>

    <!-- Search Events Button -->


    <a href="<?= isset($_SESSION['user_id']) ? BASE_URL . 'events.php' : BASE_URL . 'login.php' ?>" class="btn btn-outline-secondary btn-lg mt-3">
        Manage Your Events
    </a>

</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>