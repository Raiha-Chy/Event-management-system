<?php
require_once __DIR__ . '/config/database.php';

$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
$stmt = $pdo->prepare("SELECT b.*, e.title AS event_title, e.event_date, e.event_time, e.ticket_price, v.name AS venue_name, v.location, a.name AS attendee_name, a.email, a.phone
FROM bookings b
LEFT JOIN events e ON b.event_id = e.id
LEFT JOIN venues v ON e.venue_id = v.id
LEFT JOIN attendees a ON b.attendee_id = a.id
WHERE b.id = ?");
$stmt->execute([$booking_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>
<?php if($ticket): ?>
<div class="card p-4 ticket-card">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Event Ticket</h1>
            <p class="text-muted mb-0">Booking ID: <?= $ticket['id'] ?></p>
        </div>
        <button class="btn btn-dark" onclick="window.print()">Print Ticket</button>
    </div>
    <div class="row g-3">
        <div class="col-md-6"><strong>Attendee:</strong> <?= htmlspecialchars($ticket['attendee_name']) ?></div>
        <div class="col-md-6"><strong>Email:</strong> <?= htmlspecialchars($ticket['email']) ?></div>
        <div class="col-md-6"><strong>Phone:</strong> <?= htmlspecialchars($ticket['phone']) ?></div>
        <div class="col-md-6"><strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?></div>
        <div class="col-md-6"><strong>Event:</strong> <?= htmlspecialchars($ticket['event_title']) ?></div>
        <div class="col-md-6"><strong>Date:</strong> <?= htmlspecialchars($ticket['event_date']) ?></div>
        <div class="col-md-6"><strong>Time:</strong> <?= htmlspecialchars($ticket['event_time']) ?></div>
        <div class="col-md-6"><strong>Venue:</strong> <?= htmlspecialchars($ticket['venue_name']) ?></div>
        <div class="col-md-6"><strong>Location:</strong> <?= htmlspecialchars($ticket['location']) ?></div>
        <div class="col-md-6"><strong>Quantity:</strong> <?= htmlspecialchars($ticket['quantity']) ?></div>
        <div class="col-md-6"><strong>Unit Price:</strong> ৳<?= htmlspecialchars($ticket['ticket_price']) ?></div>
        <div class="col-md-6"><strong>Total:</strong> ৳<?= number_format($ticket['ticket_price'] * $ticket['quantity'], 2) ?></div>
        <div class="col-md-6"><strong>Booking Date:</strong> <?= htmlspecialchars($ticket['booking_date']) ?></div>
    </div>
    <hr>
    <p class="mb-0 text-muted">Show this ticket at event entry. For PDF, use browser Print and save as PDF.</p>
</div>
<?php else: ?>
<div class="card p-4 text-center"><h2 class="h4">Ticket not found</h2></div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>