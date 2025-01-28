<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/EventController.php';

// Check authentication
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

$eventController = new EventController($pdo);

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Sorting and filtering
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get paginated events
$events = $eventController->getPaginatedEvents($limit, $offset, $sort, $search);
$totalEvents = $eventController->getTotalEventsCount($search);
$totalPages = ceil($totalEvents / $limit);
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">Event Dashboard</h2>
        <div>

            <a href="events.php" class="btn btn-outline-primary btn-primary text-white">Create Event</a>
        </div>

    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" placeholder="Search events..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-4 text-md-right">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="dashboard.php" class="btn btn-outline-secondary">Clear Search</a>
                </div>
            </form>

            <form method="GET" class="form-inline mb-3">
                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                <label class="mr-2">Sort by:</label>
                <select name="sort" class="custom-select w-auto" onchange="this.form.submit()">
                    <option value="name" <?= $sort == 'name' ? 'selected' : '' ?>>Name</option>
                    <option value="date" <?= $sort == 'date' ? 'selected' : '' ?>>Date</option>
                    <option value="capacity" <?= $sort == 'capacity' ? 'selected' : '' ?>>Capacity</option>
                </select>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($events)): ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?= htmlspecialchars($event['name']) ?></td>
                                    <td><?= htmlspecialchars($event['description']) ?></td>
                                    <td><?= htmlspecialchars($event['date']) ?></td>
                                    <td><?= htmlspecialchars($event['location']) ?></td>
                                    <td><?= htmlspecialchars($event['capacity']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No events found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&sort=<?= htmlspecialchars($sort) ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>