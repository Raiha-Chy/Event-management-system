<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM venues WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    header("Location: venues.php");
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM venues WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $capacity = intval($_POST['capacity']);

    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE venues SET name=?, location=?, capacity=? WHERE id=?");
        $stmt->execute([$name, $location, $capacity, intval($_POST['id'])]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO venues (name, location, capacity) VALUES (?, ?, ?)");
        $stmt->execute([$name, $location, $capacity]);
    }
    header("Location: venues.php");
    exit;
}

$rows = $pdo->query("SELECT * FROM venues ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 mb-3"><?= $edit ? 'Edit Venue' : 'Add Venue' ?></h2>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
                <div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control" required value="<?= htmlspecialchars($edit['name'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">Location</label><input name="location" class="form-control" required value="<?= htmlspecialchars($edit['location'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">Capacity</label><input type="number" name="capacity" class="form-control" required value="<?= htmlspecialchars($edit['capacity'] ?? '') ?>"></div>
                <button class="btn btn-dark"><?= $edit ? 'Update' : 'Save' ?></button>
                <a href="venues.php" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4">
            <h2 class="h5 mb-3">Venue List</h2>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>ID</th><th>Name</th><th>Location</th><th>Capacity</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= htmlspecialchars($row['capacity']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="venues.php?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this venue?')" href="venues.php?delete=<?= $row['id'] ?>">Delete</a>
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