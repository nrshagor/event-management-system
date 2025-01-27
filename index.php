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

<div class="container text-center mt-5 mb-5">
    <h1 class="landing-title">Welcome to Event <span class="text-orange">Manager</span> </h1>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Event Calendar</h2>
        <div id="calendar"></div>
    </div>

    <p class="sub-title">Discover and register for upcoming events with ease!</p>

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
                                <a href="<?= BASE_URL ?>register_attendee.php?event_id=<?= $event['id'] ?>" class="btn btn-primary btn-block">
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

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="eventImage" src="" class="img-fluid rounded mb-3" alt="Event Image" style="max-height: 300px;">
                    </div>
                    <h3 id="eventTitle" class="text-primary"></h3>
                    <p id="eventDate"></p>
                    <p id="eventLocation"></p>
                    <p id="eventDescription" class="text-muted"></p>

                    <div class="progress mb-3">
                        <div id="eventProgressBar" class="progress-bar bg-success" role="progressbar"
                            style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>

                    <p id="eventCapacity" class="text-muted"></p>
                </div>
                <div class="modal-footer">
                    <a href="#" id="eventRegisterLink" class="btn btn-primary">Register Now</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>