<?php
require_once __DIR__ . '/app/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    redirect('public/login.php');
}

$stmt = $pdo->query("SELECT id, username, email, role FROM users WHERE role != 'super_admin'");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle role change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['role'];

    $updateStmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $updateStmt->execute([$new_role, $user_id]);
    header("Location: manage_users.php?success=1");
}
?>

<?php include __DIR__ . '/app/views/header.php'; ?>

<h2>Manage Users</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">User role updated successfully!</div>
<?php endif; ?>
<div class="table-responsive">
    <table class="table table-primary table-bordered ">
        <thead class="table">

            <tr class="table-info">
                <th>Username</th>
                <th>Email</th>
                <th>Current Role</th>
                <th>Change Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <div class="d-md-flex  align-items-center gap-3">
                                <select name=" role" class="form-control mr-5">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit" class="action-btn-1  mt-2 mt-md-0">Update</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>

<?php include __DIR__ . '/app/views/footer.php'; ?>