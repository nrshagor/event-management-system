<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/EventController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventController = new EventController($pdo);
    $message = $eventController->createEvent($_POST['name'], $_POST['description'], $_POST['date'], $_POST['location'], $_POST['capacity']);
    header("Location: index.php?message=" . urlencode($message));
    exit;
}
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container mt-5">
    <h2>Add Event</h2>
    <form action="add_event.php" method="POST">
        <input type="text" name="name" placeholder="Event Name" class="form-control mb-3" required>
        <textarea name="description" placeholder="Description" class="form-control mb-3" required></textarea>
        <input type="date" name="date" class="form-control mb-3" required>
        <input type="text" name="location" placeholder="Location" class="form-control mb-3" required>
        <input type="number" name="capacity" placeholder="Capacity" class="form-control mb-3" required>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>