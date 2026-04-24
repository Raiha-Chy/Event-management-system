CREATE DATABASE IF NOT EXISTS event_management_system;
USE event_management_system;

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS attendees;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS venues;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE venues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    location VARCHAR(255) NOT NULL,
    capacity INT NOT NULL
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    image_url VARCHAR(255) DEFAULT NULL,
    venue_id INT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    capacity INT NOT NULL,
    ticket_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_events_venue FOREIGN KEY (venue_id) REFERENCES venues(id) ON DELETE CASCADE
);

CREATE TABLE attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    attendee_id INT NOT NULL,
    booking_date DATE NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    status VARCHAR(50) NOT NULL DEFAULT 'Confirmed',
    CONSTRAINT fk_bookings_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    CONSTRAINT fk_bookings_attendee FOREIGN KEY (attendee_id) REFERENCES attendees(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (name, email, password) VALUES
('Administrator', 'admin@example.com', '$2y$12$ame5.8KNusHhuCoFeBQWxe7LB8dGUyeq/KNRTkB730Dryb1bov5GW');

INSERT INTO venues (name, location, capacity) VALUES
('Convention Hall', 'Dhaka', 500),
('Auditorium A', 'Chattogram', 250),
('Open Air Arena', 'Sylhet', 800);

INSERT INTO events (title, description, image_url, venue_id, event_date, event_time, capacity, ticket_price) VALUES
('Tech Conference 2026', 'Annual technology conference for developers, founders and IT professionals.', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80', 1, '2026-05-20', '10:00:00', 300, 1500.00),
('Startup Workshop', 'Workshop for founders and innovators with expert sessions and networking.', 'https://images.unsplash.com/photo-1515169067868-5387ec356754?auto=format&fit=crop&w=1200&q=80', 2, '2026-05-28', '14:00:00', 150, 500.00),
('Music Fest Night', 'A live outdoor musical event with multiple performers and premium seating.', 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?auto=format&fit=crop&w=1200&q=80', 3, '2026-06-10', '18:30:00', 600, 2000.00);

INSERT INTO attendees (name, email, phone) VALUES
('Rahim Uddin', 'rahim@example.com', '01700000001'),
('Karim Hasan', 'karim@example.com', '01700000002');

INSERT INTO bookings (event_id, attendee_id, booking_date, quantity, status) VALUES
(1, 1, '2026-04-01', 2, 'Confirmed'),
(2, 2, '2026-04-02', 1, 'Pending');

INSERT INTO contact_messages (name, email, subject, message) VALUES
('Demo User', 'demo@example.com', 'Need event info', 'Please share more information about booking process.');
