<?php
require_once __DIR__ . '/app/controllers/AuthController.php';
$auth = new AuthController();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // Server-side validation
    if (empty($username) || strlen($username) < 3) {
        $error = "Username must be at least 3 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        if ($auth->register($username, $email, $password)) {
            header("Location: login.php?success=1");
            exit;
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-success text-white text-center">
                <h3 class="mb-0">Create an Account</h3>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST" id="registerForm">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required minlength="3">
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn-success btn-block">Register</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <small>Already have an account? <a href="login.php">Login here</a></small>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/app/views/footer.php'; ?>