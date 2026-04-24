<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$summary = [
    'events' => $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn(),
    'venues' => $pdo->query("SELECT COUNT(*) FROM venues")->fetchColumn(),
    'attendees' => $pdo->query("SELECT COUNT(*) FROM attendees")->fetchColumn(),
    'bookings' => $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn(),
    'revenue' => $pdo->query("SELECT IFNULL(SUM(e.ticket_price * b.quantity),0) FROM bookings b LEFT JOIN events e ON b.event_id = e.id WHERE b.status='Confirmed'")->fetchColumn()
];

$rows = $pdo->query("SELECT b.id, e.title AS event_title, a.name AS attendee_name, b.quantity, b.status, b.booking_date, e.ticket_price, (e.ticket_price * b.quantity) AS total_amount
FROM bookings b
LEFT JOIN events e ON b.event_id = e.id
LEFT JOIN attendees a ON b.attendee_id = a.id
ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Reports</h1>
        <p class="text-muted mb-0">Summary and print-ready booking report.</p>
    </div>
    <button class="btn btn-dark" onclick="window.print()">Print Report</button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-2"><div class="card p-3"><div class="small text-muted">Events</div><div class="h3 mb-0"><?= $summary['events'] ?></div></div></div>
    <div class="col-md-2"><div class="card p-3"><div class="small text-muted">Venues</div><div class="h3 mb-0"><?= $summary['venues'] ?></div></div></div>
    <div class="col-md-2"><div class="card p-3"><div class="small text-muted">Attendees</div><div class="h3 mb-0"><?= $summary['attendees'] ?></div></div></div>
    <div class="col-md-2"><div class="card p-3"><div class="small text-muted">Bookings</div><div class="h3 mb-0"><?= $summary['bookings'] ?></div></div></div>
    <div class="col-md-4"><div class="card p-3"><div class="small text-muted">Confirmed Revenue</div><div class="h3 mb-0">৳<?= number_format($summary['revenue'],2) ?></div></div></div>
</div>

<div class="card p-4">
    <h2 class="h5 mb-3">Booking Report</h2>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead><tr><th>ID</th><th>Event</th><th>Attendee</th><th>Qty</th><th>Status</th><th>Date</th><th>Total</th></tr></thead>
            <tbody>
            <?php foreach($rows as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['event_title']) ?></td>
                    <td><?= htmlspecialchars($row['attendee_name']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['booking_date']) ?></td>
                    <td>৳<?= number_format($row['total_amount'],2) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if(!$rows): ?><tr><td colspan="7" class="text-center">No data found.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>