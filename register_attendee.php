<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/AttendeeController.php';
require_once __DIR__ . '/app/controllers/EventController.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
//     redirect('public/login.php');
// }

$attendeeController = new AttendeeController($pdo);
$eventController = new EventController($pdo);
$events = $eventController->getEventsWithoutUserId();

// Check if event_id is passed in the URL
$selected_event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : '';
$selected_event = null;

if ($selected_event_id) {
    foreach ($events as $event) {
        if ($event['id'] == $selected_event_id) {
            $selected_event = $event;
            break;
        }
    }
}
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Register for an Event</h2>

    <?php if ($selected_event): ?>
        <!-- Display the event image as a banner -->
        <div class="event-banner text-center mb-4">
            <img src="<?= BASE_URL ?>public/uploads/<?= $selected_event['image']; ?>"
                alt="<?= htmlspecialchars($selected_event['name']); ?>"
                class="img-fluid rounded shadow"
                style="max-height: 300px; width: 100%; object-fit: cover;">
        </div>

        <div class="alert alert-info">
            <h4>Event Details:</h4>
            <p><strong>Name:</strong> <?= htmlspecialchars($selected_event['name']); ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($selected_event['date']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($selected_event['location']); ?></p>
            <p><strong>Capacity:</strong> <?= htmlspecialchars($selected_event['capacity']); ?></p>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No event selected. Please choose from the dropdown.</div>
    <?php endif; ?>

    <form id="eventRegistrationForm" class="p-4 border rounded shadow-sm bg-light">
        <?php if ($selected_event_id): ?>
            <input type="hidden" name="event_id" value="<?= $selected_event_id ?>">
        <?php else: ?>
            <div class="form-group pb-3">
                <label for="event_id">Select Event</label>
                <select name="event_id" id="event_id" class="form-control" required>
                    <option value="">-- Select Event --</option>
                    <?php foreach ($events as $event): ?>
                        <option value="<?= $event['id'] ?>">
                            <?= htmlspecialchars($event['name']) ?> (Capacity: <?= $event['capacity'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="form-group pb-3">
            <label for="user_name">Your Name</label>
            <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter your name" required>
        </div>

        <div class="form-group pb-3">
            <label for="email">Your Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>

        <button type="submit" class="btn btn-success btn-block">Register</button>
        <a href="events.php" class="btn btn-light ">Back</a>
    </form>

    <div id="responseMessage" class="mt-3 text-center"></div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    $(document).ready(function() {
        $("#eventRegistrationForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: '<?= BASE_URL ?>public/ajax/ajax_register.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $("#responseMessage").html('<div class="alert alert-info">Processing...</div>');
                },
                success: function(response) {
                    if (response.success) {
                        $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                        $("#eventRegistrationForm")[0].reset();
                    } else {
                        $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $("#responseMessage").html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
                }
            });
        });

        $("#event_id").change(function() {
            var eventId = $(this).val();
            if (eventId) {
                $.get('api/event_details.php', {
                    event_id: eventId
                }, function(response) {
                    if (response.success) {
                        $(".event-banner").html('<img src="' + response.event.image_url +
                            '" class="img-fluid rounded shadow" style="max-height: 300px; width: 100%; object-fit: cover;">');
                    } else {
                        $(".event-banner").html('<div class="alert alert-danger">Event not found!</div>');
                    }
                }, 'json');
            }
        });
    });
</script>

<?php include __DIR__ . '/app/views/footer.php'; ?>