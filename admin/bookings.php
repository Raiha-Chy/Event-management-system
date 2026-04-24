<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([intval($_GET['delete'])]);
    header("Location: bookings.php");
    exit;
}

$events = $pdo->query("SELECT id, title FROM events ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);
$attendees = $pdo->query("SELECT id, name FROM attendees ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$edit = null;

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([intval($_GET['edit'])]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $attendee_id = intval($_POST['attendee_id']);
    $booking_date = $_POST['booking_date'];
    $quantity = intval($_POST['quantity']);
    $status = trim($_POST['status']);

    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE bookings SET event_id=?, attendee_id=?, booking_date=?, quantity=?, status=? WHERE id=?");
        $stmt->execute([$event_id, $attendee_id, $booking_date, $quantity, $status, intval($_POST['id'])]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO bookings (event_id, attendee_id, booking_date, quantity, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$event_id, $attendee_id, $booking_date, $quantity, $status]);
    }
    header("Location: bookings.php");
    exit;
}

$rows = $pdo->query("SELECT b.*, e.title AS event_title, a.name AS attendee_name 
FROM bookings b
LEFT JOIN events e ON b.event_id = e.id
LEFT JOIN attendees a ON b.attendee_id = a.id
ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <h2 class="h5 mb-3"><?= $edit ? 'Edit Booking' : 'Add Booking' ?></h2>
            <form method="post">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
                <div class="mb-3">
                    <label class="form-label">Event</label>
                    <select name="event_id" class="form-select" required>
                        <option value="">Select event</option>
                        <?php foreach($events as $event): ?>
                            <option value="<?= $event['id'] ?>" <?= isset($edit['event_id']) && $edit['event_id'] == $event['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($event['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Attendee</label>
                    <select name="attendee_id" class="form-select" required>
                        <option value="">Select attendee</option>
                        <?php foreach($attendees as $attendee): ?>
                            <option value="<?= $attendee['id'] ?>" <?= isset($edit['attendee_id']) && $edit['attendee_id'] == $attendee['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($attendee['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Booking Date</label><input type="date" name="booking_date" class="form-control" required value="<?= htmlspecialchars($edit['booking_date'] ?? date('Y-m-d')) ?>"></div>
                <div class="mb-3"><label class="form-label">Quantity</label><input type="number" name="quantity" class="form-control" required value="<?= htmlspecialchars($edit['quantity'] ?? '1') ?>"></div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <?php $statusValue = $edit['status'] ?? 'Confirmed'; ?>
                        <?php foreach(['Confirmed', 'Pending', 'Cancelled'] as $status): ?>
                            <option value="<?= $status ?>" <?= $statusValue === $status ? 'selected' : '' ?>><?= $status ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="btn btn-dark"><?= $edit ? 'Update' : 'Save' ?></button>
                <a href="bookings.php" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card p-4">
            <h2 class="h5 mb-3">Booking List</h2>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>ID</th><th>Event</th><th>Attendee</th><th>Date</th><th>Qty</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['event_title'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['attendee_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['booking_date']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="bookings.php?edit=<?= $row['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" onclick="return confirm('Delete this booking?')" href="bookings.php?delete=<?= $row['id'] ?>">Delete</a>
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