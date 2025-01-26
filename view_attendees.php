<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/AttendeeController.php';

$attendeeController = new AttendeeController($pdo);
$event_id = intval($_GET['event_id'] ?? 0);

// Pagination settings
$limit = intval($_GET['limit'] ?? 10);
$search = trim($_GET['search'] ?? '');
$page = intval($_GET['page'] ?? 1);
$offset = ($page - 1) * $limit;

// Get attendees and total count
$attendees = $attendeeController->listAttendees($event_id, $limit, $offset, $search);
$totalAttendees = $attendeeController->countAttendees($event_id, $search);
$totalPages = ceil($totalAttendees / $limit);
?>

<?php include __DIR__ . '/app/views/header.php'; ?>
<div class="container mt-4">
    <a href="dashboard.php" class="btn btn-light mb-3">Back</a>
    <div class="card shadow  border-0 p-4">


        <h2 class="text-center mb-4 text-dark">Attendees List</h2>

        <!-- Search and Limit Options -->
        <div class="row g-2 align-items-center">
            <div class="col-md-9">
                <form method="GET" class="form-inline mb-3 row g-2">
                    <input type="hidden" name="event_id" value="<?= $event_id ?>">

                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name or email"
                            value="<?= htmlspecialchars($search) ?>">
                    </div>

                    <div class="col-md-3">
                        <select name="limit" class="form-control">
                            <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                    </div>
                </form>
            </div>

            <div class="col-md-3 mb-3 text-md-end">
                <!-- Export Button -->
                <a href="export_attendees.php?event_id=<?= $event_id ?>" class="btn btn-success w-100">
                    Download CSV
                </a>
            </div>
        </div>



        <!-- Responsive Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attendees)): ?>
                        <?php foreach ($attendees as $attendee): ?>
                            <tr>
                                <td><?= htmlspecialchars($attendee['user_name']) ?></td>
                                <td><?= htmlspecialchars($attendee['email']) ?></td>
                                <td><?= htmlspecialchars($attendee['registered_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-danger">No attendees found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?event_id=<?= $event_id ?>&search=<?= htmlspecialchars($search) ?>&limit=<?= $limit ?>&page=<?= $page - 1 ?>">Previous</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?event_id=<?= $event_id ?>&search=<?= htmlspecialchars($search) ?>&limit=<?= $limit ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="?event_id=<?= $event_id ?>&search=<?= htmlspecialchars($search) ?>&limit=<?= $limit ?>&page=<?= $page + 1 ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>

</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>