<?php
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/controllers/EventController.php';

$eventController = new EventController($pdo);

// Get the search query
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$events = $eventController->searchEvents($search);
?>

<?php include __DIR__ . '/app/views/header.php'; ?>






<div class="container mt-4 mb-4">
    <a href=<?= BASE_URL ?> class="btn btn-light mb-3">Back</a>
    <div class="card shadow  border-0 p-4">


        <h2 class="text-center mb-4 text-dark">Search Results</h2>


        <!-- Search Form -->
        <form method="GET" action="search_results.php" class="form-inline justify-content-center mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control form-control-lg  shadow-sm" placeholder="Search events..." value="<?= $search ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-lg ml-2 shadow-sm">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>

            </div>
        </form>




        <div class="row">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div class="image-container">
                                <img src="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($event['image']) ?>"
                                    class="card-img-top rounded-top img-fluid"
                                    alt="<?= htmlspecialchars($event['name']) ?>"
                                    onerror="this.onerror=null;this.src='<?= BASE_URL ?>public/uploads/default-event.jpg';">
                            </div>
                            <div class="card-body">
                                <h4 class="text-primary font-weight-bold"><?= htmlspecialchars($event['name']) ?></h4>
                                <p class="text-muted"><?= htmlspecialchars($event['description']) ?></p>
                                <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event['date'])) ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                                <a href="<?= BASE_URL ?>public/register_attendee.php?event_id=<?= $event['id'] ?>" class="btn btn-success btn-block font-weight-bold">
                                    Register Now
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">No events found for your search query.</div>
                </div>
            <?php endif; ?>
        </div>


    </div>
</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>