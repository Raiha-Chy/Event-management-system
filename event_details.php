<?php
require_once __DIR__ . '/config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT e.*, v.name AS venue_name, v.location AS venue_location FROM events e
LEFT JOIN venues v ON e.venue_id = v.id WHERE e.id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

include __DIR__ . '/includes/header.php';
?>
<?php if($event): ?>
<div class="card p-4">
    <div class="row g-4">
        <div class="col-lg-6">
            <?php if(!empty($event['image_url'])): ?>
                <img src="<?= htmlspecialchars($event['image_url']) ?>" class="w-100 rounded-4" style="height:380px;object-fit:cover;" alt="event image">
            <?php else: ?>
                <div class="w-100 rounded-4 bg-light d-flex align-items-center justify-content-center text-muted" style="height:380px;">No Image</div>
            <?php endif; ?>
        </div>
        <div class="col-lg-6">
            <span class="badge text-bg-dark mb-2">Event Details</span>
            <h1 class="h2 mb-3"><?= htmlspecialchars($event['title']) ?></h1>
            <p class="text-muted"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
            <div class="small-muted mb-2"><strong>Venue:</strong> <?= htmlspecialchars($event['venue_name'] ?? 'N/A') ?></div>
            <div class="small-muted mb-2"><strong>Location:</strong> <?= htmlspecialchars($event['venue_location'] ?? 'N/A') ?></div>
            <div class="small-muted mb-2"><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></div>
            <div class="small-muted mb-2"><strong>Time:</strong> <?= htmlspecialchars($event['event_time']) ?></div>
            <div class="small-muted mb-2"><strong>Capacity:</strong> <?= htmlspecialchars($event['capacity']) ?></div>
            <div class="small-muted mb-4"><strong>Ticket Price:</strong> ৳<?= htmlspecialchars($event['ticket_price']) ?></div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/event_management_system/booking.php?event_id=<?= $event['id'] ?>" class="btn btn-warning">Book This Event</a>
                <a href="/event_management_system/index.php" class="btn btn-outline-dark">Back Home</a>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card p-4 text-center"><h2 class="h4">Event not found</h2><a href="/event_management_system/index.php" class="btn btn-dark mt-2">Back Home</a></div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>