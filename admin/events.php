<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    header("Location: events.php");
    exit;
}

$venues = $pdo->query("SELECT * FROM venues ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);
    $venue_id = intval($_POST['venue_id']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $capacity = intval($_POST['capacity']);
    $ticket_price = floatval($_POST['ticket_price']);

    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, image_url=?, venue_id=?, event_date=?, event_time=?, capacity=?, ticket_price=? WHERE id=?");
        $stmt->execute([$title, $description, $image_url, $venue_id, $event_date, $event_time, $capacity, $ticket_price, intval($_POST['id'])]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, image_url, venue_id, event_date, event_time, capacity, ticket_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_url, $venue_id, $event_date, $event_time, $capacity, $ticket_price]);
    }
    header("Location: events.php");
    exit;
}

$rows = $pdo->query("SELECT e.*, v.name AS venue_name FROM events e LEFT JOIN venues v ON e.venue_id = v.id ORDER BY e.id DESC")->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 mb-3"><?= $edit ? 'Edit Event' : 'Add Event' ?></h2>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input name="title" class="form-control" required value="<?= htmlspecialchars($edit['title'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image URL</label>
                    <input name="image_url" class="form-control" value="<?= htmlspecialchars($edit['image_url'] ?? '') ?>" placeholder="https://example.com/image.jpg">
                </div>
                <div class="mb-3">
                    <label class="form-label">Venue</label>
                    <select name="venue_id" class="form-select" required>
                        <option value="">Select venue</option>
                        <?php foreach($venues as $venue): ?>
                            <option value="<?= $venue['id'] ?>" <?= isset($edit['venue_id']) && $edit['venue_id'] == $venue['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($venue['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="event_date" class="form-control" required value="<?= htmlspecialchars($edit['event_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" name="event_time" class="form-control" required value="<?= htmlspecialchars($edit['event_time'] ?? '') ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" required value="<?= htmlspecialchars($edit['capacity'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ticket Price</label>
                        <input type="number" step="0.01" name="ticket_price" class="form-control" required value="<?= htmlspecialchars($edit['ticket_price'] ?? '0') ?>">
                    </div>
                </div>
                <button class="btn btn-dark"><?= $edit ? 'Update' : 'Save' ?></button>
                <a href="events.php" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4">
            <h2 class="h5 mb-3">Event List</h2>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>ID</th><th>Title</th><th>Image</th><th>Venue</th><th>Date</th><th>Price</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?php if(!empty($row['image_url'])): ?><img src="<?= htmlspecialchars($row['image_url']) ?>" alt="event" style="width:60px;height:40px;object-fit:cover;border-radius:8px;"><?php else: ?>N/A<?php endif; ?></td>
                            <td><?= htmlspecialchars($row['venue_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['event_date']) ?></td>
                            <td><?= htmlspecialchars($row['ticket_price']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="events.php?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?')" href="events.php?delete=<?= $row['id'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(!$rows): ?><tr><td colspan="7" class="text-center">No data found.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>