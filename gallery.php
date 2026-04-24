<?php
require_once __DIR__ . '/config/database.php';
$events = $pdo->query("SELECT id, title, image_url, event_date FROM events ORDER BY event_date ASC")->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-1">Event Gallery</h1>
        <p class="text-muted mb-0">A visual gallery of available events.</p>
    </div>
</div>
<div class="row g-4">
    <?php foreach($events as $event): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card p-3 h-100">
            <?php if(!empty($event['image_url'])): ?>
                <img src="<?= htmlspecialchars($event['image_url']) ?>" class="gallery-image mb-3" alt="gallery image">
            <?php else: ?>
                <div class="gallery-image mb-3 bg-light d-flex align-items-center justify-content-center text-muted">No Image</div>
            <?php endif; ?>
            <h3 class="h5"><?= htmlspecialchars($event['title']) ?></h3>
            <p class="text-muted small mb-3"><?= htmlspecialchars($event['event_date']) ?></p>
            <a href="/event_management_system/event_details.php?id=<?= $event['id'] ?>" class="btn btn-outline-dark">View Details</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>