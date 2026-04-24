<?php
require_once __DIR__ . '/config/database.php';

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("
        SELECT 
            e.*, 
            v.name AS venue_name, 
            v.location AS venue_location
        FROM events e
        LEFT JOIN venues v ON e.venue_id = v.id
        WHERE 
            e.title LIKE ? 
            OR e.description LIKE ? 
            OR v.name LIKE ? 
            OR v.location LIKE ?
        ORDER BY e.event_date ASC
        LIMIT 12
    ");

    $like = "%$search%";
    $stmt->execute([$like, $like, $like, $like]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $events = $pdo->query("
        SELECT 
            e.*, 
            v.name AS venue_name, 
            v.location AS venue_location
        FROM events e
        LEFT JOIN venues v ON e.venue_id = v.id
        ORDER BY e.event_date ASC
        LIMIT 12
    ")->fetchAll(PDO::FETCH_ASSOC);
}

$totalEvents = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$totalVenues = $pdo->query("SELECT COUNT(*) FROM venues")->fetchColumn();
$totalAttendees = $pdo->query("SELECT COUNT(*) FROM attendees")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

include __DIR__ . '/includes/header.php';
?>

<section class="hero p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <h1 class="display-5 fw-bold mb-3">
                Create, manage and experience unforgettable events — all in one place.
            </h1>
            <p class="lead text-light opacity-75 mb-4">
                Easily organize events, manage venues, handle bookings, showcase galleries,
                and generate reports with a powerful and user-friendly event management system.
            </p>

            <div class="d-flex flex-wrap gap-2">
                <a href="/event_management_system/booking.php" class="btn btn-warning btn-lg">
                    Book Now
                </a>
                <a href="#events" class="btn btn-outline-light btn-lg">
                    Browse Events
                </a>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="soft-box">
                <h3 class="h4 mb-3 text-dark">System Overview</h3>

                <div class="row g-3">
                    <div class="col-6" style="color: #000;">
                        <div class="border rounded-4 p-3 text-center">
                            <div class="fs-3 fw-bold"><?= $totalEvents ?></div>
                            <div class="text-muted">Events</div>
                        </div>
                    </div>

                    <div class="col-6" style="color: #000;">
                        <div class="border rounded-4 p-3 text-center">
                            <div class="fs-3 fw-bold"><?= $totalVenues ?></div>
                            <div class="text-muted">Venues</div>
                        </div>
                    </div>

                    <div class="col-6" style="color: #000;">
                        <div class="border rounded-4 p-3 text-center">
                            <div class="fs-3 fw-bold"><?= $totalAttendees ?></div>
                            <div class="text-muted">Attendees</div>
                        </div>
                    </div>

                    <div class="col-6" style="color: #000;">
                        <div class="border rounded-4 p-3 text-center">
                            <div class="fs-3 fw-bold"><?= $totalBookings ?></div>
                            <div class="text-muted">Bookings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="card p-4">
        <div class="row align-items-end g-3">
            <div class="col-lg-8">
                <h2 class="h4 mb-1">Search Events</h2>
                <p class="text-muted mb-0">
                    Search by event title, description, venue or location.
                </p>
            </div>

            <div class="col-lg-4">
                <form method="get" class="d-flex gap-2">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Search events..."
                    >
                    <button class="btn btn-dark">Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="mb-4">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="feature-icon mb-3">📅</div>
                <h3 class="h5">Event</h3>
                <p class="text-muted mb-0">
                    Turn your ideas into unforgettable events.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="feature-icon mb-3">🖼️</div>
                <h3 class="h5">Gallery</h3>
                <p class="text-muted mb-0">
                    Relive moments through stunning event highlights.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="feature-icon mb-3">🎫</div>
                <h3 class="h5">PDF Ticket</h3>
                <p class="text-muted mb-0">
                    Book your spot and get instant event access.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100">
                <div class="feature-icon mb-3">🖨️</div>
                <h3 class="h5">Reports</h3>
                <p class="text-muted mb-0">
                    Track activity and insights with ease.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="events" class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="section-title h3 mb-1">Upcoming Events</h2>
            <p class="text-muted mb-0">
                <?= $search !== '' ? 'Search results from your database.' : 'Live event list from your database.' ?>
            </p>
        </div>

        <a href="/event_management_system/gallery.php" class="btn btn-outline-dark">
            View Gallery
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($events as $event): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card p-4 h-100 event-card">
                    <?php if (!empty($event['image_url'])): ?>
                        <img 
                            src="<?= htmlspecialchars($event['image_url']) ?>" 
                            class="event-image mb-3" 
                            alt="event image"
                        >
                    <?php else: ?>
                        <div class="event-image mb-3 d-flex align-items-center justify-content-center bg-light text-muted">
                            No Image
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge text-bg-dark">Event</span>
                        <span class="badge text-bg-light border">
                            ৳<?= htmlspecialchars($event['ticket_price']) ?>
                        </span>
                    </div>

                    <h3 class="h5"><?= htmlspecialchars($event['title']) ?></h3>

                    <p class="text-muted small mb-2">
                        <?= nl2br(htmlspecialchars(substr($event['description'] ?? '', 0, 110))) ?>
                    </p>

                    <ul class="list-unstyled small mb-3">
                        <li><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></li>
                        <li><strong>Time:</strong> <?= htmlspecialchars($event['event_time']) ?></li>
                        <li><strong>Venue:</strong> <?= htmlspecialchars($event['venue_name'] ?? 'N/A') ?></li>
                        <li><strong>Location:</strong> <?= htmlspecialchars($event['venue_location'] ?? 'N/A') ?></li>
                    </ul>

                    <div class="mt-auto d-grid gap-2">
                        <a href="/event_management_system/event_details.php?id=<?= $event['id'] ?>" class="btn btn-dark">
                            View Details
                        </a>
                        <a href="/event_management_system/booking.php?event_id=<?= $event['id'] ?>" class="btn btn-warning">
                            Book This Event
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$events): ?>
            <div class="col-12">
                <div class="card p-4 text-center">
                    <h3 class="h5">No events found</h3>
                    <p class="text-muted mb-0">
                        Try another search or add events from the admin panel.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>