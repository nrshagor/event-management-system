<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/EventController.php';

if (!isset($_SESSION['user_id'])) {
    redirect('public/login.php');
}

$eventController = new EventController($pdo);

// Handle Event Deletion via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event'])) {
    $eventId = intval($_POST['delete_event']);
    if ($eventController->deleteEvent($eventId)) {
        echo json_encode(["success" => true, "message" => "Event deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting event."]);
    }
    exit;
}

// Fetch Events
$events = $eventController->getEvents();
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-lg border-0 p-4">
        <h2 class="text-center mb-4 text-primary">Manage Your Events</h2>

        <form id="eventForm" class="mb-4" enctype="multipart/form-data" method="POST">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Event Name</strong></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Event Name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Date</strong></label>
                        <input type="datetime-local" name="date" id="date" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><strong>Description</strong></label>
                <textarea name="description" class="form-control" placeholder="Enter Event Description" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Location</strong></label>
                        <input type="text" name="location" class="form-control" placeholder="Enter Event Location" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Capacity</strong></label>
                        <input type="number" name="capacity" class="form-control" placeholder="Enter Capacity" required min="1">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label><strong>Event Image</strong> (Optional)</label>
                <input type="file" name="event_image" class="form-control" id="event_image">
                <small class="text-muted">Max file size: 10MB</small>

            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">
                <i class="fas fa-plus-circle"></i> Create Event
            </button>
        </form>


        <div id="responseMessage"></div>

        <h3 class="mt-5 text-secondary">Your Events</h3>
        <div class="table-responsive">
            <table id="eventTable" class="table table-striped table-bordered shadow-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="eventList">
                    <?php foreach ($events as $event): ?>
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
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                                <a href="view_attendees.php?event_id=<?= $event['id'] ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>



<!-- DataTables for pagination and searching -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        // Date validation to prevent past dates
        const dateInput = $('#date');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        dateInput.attr('min', now.toISOString().slice(0, 16));
        // Initialize DataTable
        $(document).ready(function() {
            $('#eventTable').DataTable({
                "pageLength": 10, // Show 10 rows by default
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "order": [
                    [2, "asc"]
                ], // Order by date column ascending
                "searching": true, // Enable searching
                "paging": true, // Enable pagination
                "info": true // Show table information
            });
        });


        $("#eventForm").submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '<?= BASE_URL ?>/public/ajax/ajax_create_event.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    $("#responseMessage").html('<div class="alert alert-info">Processing...</div>');
                },
                success: function(response) {
                    if (response.success) {
                        $("#responseMessage").html('<div class="alert alert-success">' + response.message + '</div>');
                        $("#eventForm")[0].reset();
                        $("#eventList").append(response.html);
                    } else {
                        $("#responseMessage").html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    $("#responseMessage").html('<div class="alert alert-danger">Error: ' + xhr.status + ' - ' + error + '</div>');
                }
            });

        });


        function attachDeleteEvent() {
            $(".delete-event").off("click").on("click", function() {
                let eventId = $(this).data("id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'events.php',
                            type: 'POST',
                            data: {
                                delete_event: eventId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    $('#eventTable').DataTable().row($("#event-" + eventId)).remove().draw();
                                    Swal.fire("Deleted!", "The event has been deleted.", "success");
                                } else {
                                    Swal.fire("Error!", "There was an issue deleting the event.", "error");
                                }
                            },
                            error: function() {
                                Swal.fire("Oops!", "Something went wrong. Please try again.", "error");
                            }
                        });
                    }
                });
            });
        }

        attachDeleteEvent();
    });
</script>