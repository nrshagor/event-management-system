<?php
require_once __DIR__ . '/app/controllers/AuthController.php';

$auth = new AuthController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if ($auth->login($email, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">Login</h3>
            </div>
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group pb-4">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group pb-4">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>Don't have an account? <a href="register.php">Sign Up</a></small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>