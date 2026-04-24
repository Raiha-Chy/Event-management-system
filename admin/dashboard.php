<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$totalEvents = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$totalVenues = $pdo->query("SELECT COUNT(*) FROM venues")->fetchColumn();
$totalAttendees = $pdo->query("SELECT COUNT(*) FROM attendees")->fetchColumn();
$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();

include __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></h1>
        <p class="text-muted mb-0">Manage events, venues, attendees and bookings.</p>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-3"><div class="card p-4"><h5>Total Events</h5><h2><?= $totalEvents ?></h2></div></div>
    <div class="col-md-3"><div class="card p-4"><h5>Total Venues</h5><h2><?= $totalVenues ?></h2></div></div>
    <div class="col-md-3"><div class="card p-4"><h5>Total Attendees</h5><h2><?= $totalAttendees ?></h2></div></div>
    <div class="col-md-3"><div class="card p-4"><h5>Total Bookings</h5><h2><?= $totalBookings ?></h2></div></div>
</div>
<div class="card p-4 mt-4">
    <h2 class="h5">Quick Links</h2>
    <div class="d-flex flex-wrap gap-2">
        <a href="events.php" class="btn btn-outline-dark">Manage Events</a>
        <a href="venues.php" class="btn btn-outline-dark">Manage Venues</a>
        <a href="attendees.php" class="btn btn-outline-dark">Manage Attendees</a>
        <a href="bookings.php" class="btn btn-outline-dark">Manage Bookings</a>
        <a href="reports.php" class="btn btn-outline-dark">Reports</a>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>