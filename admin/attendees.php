<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM attendees WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    header("Location: attendees.php");
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM attendees WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE attendees SET name=?, email=?, phone=? WHERE id=?");
        $stmt->execute([$name, $email, $phone, intval($_POST['id'])]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO attendees (name, email, phone) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $phone]);
    }
    header("Location: attendees.php");
    exit;
}

$rows = $pdo->query("SELECT * FROM attendees ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 mb-3"><?= $edit ? 'Edit Attendee' : 'Add Attendee' ?></h2>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
                <div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control" required value="<?= htmlspecialchars($edit['name'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($edit['email'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">Phone</label><input name="phone" class="form-control" required value="<?= htmlspecialchars($edit['phone'] ?? '') ?>"></div>
                <button class="btn btn-dark"><?= $edit ? 'Update' : 'Save' ?></button>
                <a href="attendees.php" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4">
            <h2 class="h5 mb-3">Attendee List</h2>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="attendees.php?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this attendee?')" href="attendees.php?delete=<?= $row['id'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$rows): ?><tr><td colspan="5" class="text-center">No data found.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>