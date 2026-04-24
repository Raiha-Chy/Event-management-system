<?php
require_once __DIR__ . '/config/database.php';

$message = '';
$message_type = 'success';
$ticket_booking_id = null;
$selected_event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

$events = $pdo->query("SELECT id, title FROM events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $event_id = intval($_POST['event_id']);
    $quantity = max(1, intval($_POST['quantity']));

    if ($name && $email && $phone && $event_id) {
        $stmt = $pdo->prepare("SELECT * FROM attendees WHERE email = ?");
        $stmt->execute([$email]);
        $attendee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($attendee) {
            $attendee_id = $attendee['id'];
            $upd = $pdo->prepare("UPDATE attendees SET name=?, phone=? WHERE id=?");
            $upd->execute([$name, $phone, $attendee_id]);
        } else {
            $ins = $pdo->prepare("INSERT INTO attendees (name, email, phone) VALUES (?, ?, ?)");
            $ins->execute([$name, $email, $phone]);
            $attendee_id = $pdo->lastInsertId();
        }

        $book = $pdo->prepare("INSERT INTO bookings (event_id, attendee_id, booking_date, quantity, status) VALUES (?, ?, CURDATE(), ?, 'Confirmed')");
        $book->execute([$event_id, $attendee_id, $quantity]);
        $ticket_booking_id = $pdo->lastInsertId();

        $message = 'Booking successful.';
        $message_type = 'success';
    } else {
        $message = 'Please fill in all required fields.';
        $message_type = 'danger';
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card p-4">
            <h1 class="h3 mb-3">Book Your Event</h1>
            <p class="text-muted">This public booking form saves attendee and booking data directly into the existing system.</p>
            <?php if($message): ?><div class="alert alert-<?= $message_type ?>"><?= htmlspecialchars($message) ?></div><?php endif; ?>
            <?php if($ticket_booking_id): ?>
                <div class="mb-3">
                    <a href="/event_management_system/ticket.php?booking_id=<?= $ticket_booking_id ?>" class="btn btn-warning">View / Print Ticket</a>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Full Name</label><input name="name" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input name="phone" class="form-control" required></div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Select Event</label>
                        <select name="event_id" class="form-select" required>
                            <option value="">Choose event</option>
                            <?php foreach($events as $event): ?>
                                <option value="<?= $event['id'] ?>" <?= $selected_event_id === intval($event['id']) ? 'selected' : '' ?>><?= htmlspecialchars($event['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3"><label class="form-label">Ticket Quantity</label><input type="number" min="1" name="quantity" class="form-control" value="1" required></div>
                <button class="btn btn-dark">Confirm Booking</button>
                <a href="/event_management_system/index.php" class="btn btn-secondary">Back Home</a>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>