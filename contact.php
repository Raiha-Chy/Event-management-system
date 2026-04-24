<?php
require_once __DIR__ . '/config/database.php';
$msg = '';
$type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name && $email && $subject && $message) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        $msg = 'Your message has been sent successfully.';
        $type = 'success';
    } else {
        $msg = 'Please fill in all fields.';
        $type = 'danger';
    }
}
include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h1 class="h3 mb-3">Contact Us</h1>
            <p class="text-muted">Send an inquiry or feedback message. This will be stored in the database.</p>
            <?php if($msg): ?><div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                </div>
                <div class="mb-3"><label class="form-label">Subject</label><input name="subject" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Message</label><textarea name="message" rows="6" class="form-control" required></textarea></div>
                <button class="btn btn-dark">Send Message</button>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>