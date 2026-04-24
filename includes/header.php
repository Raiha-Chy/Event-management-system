<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/event_management_system/assets/css/custom.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/event_management_system/index.php">Event Management System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/event_management_system/index.php">Home</a>
                <a class="nav-link" href="/event_management_system/gallery.php">Gallery</a>
                <a class="nav-link" href="/event_management_system/booking.php">Booking</a>
                <a class="nav-link" href="/event_management_system/contact.php">Contact</a>
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <a class="nav-link" href="/event_management_system/admin/dashboard.php">Dashboard</a>
                    <a class="nav-link" href="/event_management_system/admin/events.php">Events</a>
                    <a class="nav-link" href="/event_management_system/admin/venues.php">Venues</a>
                    <a class="nav-link" href="/event_management_system/admin/attendees.php">Attendees</a>
                    <a class="nav-link" href="/event_management_system/admin/bookings.php">Bookings</a>
                    <a class="nav-link" href="/event_management_system/admin/reports.php">Reports</a>
                    <a class="nav-link" href="/event_management_system/admin/logout.php">Logout</a>
                <?php else: ?>
                    
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div class="container">
