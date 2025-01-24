<?php
require_once __DIR__ . '/app/config.php';


// Check authentication
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}


?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary">Event Dashboard</h2>
        <a href="dashboard.php" class="btn btn-outline-secondary">Clear Search</a>
    </div>

    <div class="card shadow-sm">
        <h1>dashboard</h1>
    </div>
</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>