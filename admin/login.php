<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card p-4">
            <h2 class="h4 mb-3">Admin Login</h2>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required value="admin@example.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required value="admin123">
                </div>
                <button class="btn btn-dark w-100">Login</button>
            </form>
            <p class="mt-3 text-muted mb-0">Default: admin@example.com / admin123</p>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>