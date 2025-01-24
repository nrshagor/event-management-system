<?php
require_once(__DIR__ . '/db.php'); // Ensure correct path

try {
    // Insert users with roles
    $pdo->exec("
        INSERT INTO users (username, email, password, role) VALUES 
        ('Super Admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_BCRYPT) . "', 'super_admin'),
        ('user1', 'user1@example.com', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'user');
    ");

    // Insert sample events
    $pdo->exec("
        INSERT INTO events (name, description, date, location, capacity, image, created_by) VALUES 
        ('Tech Conference', 'Annual tech meet', '2025-05-01 10:00:00', 'Conference Hall', 200, 'default-event.jpg', 1),
        ('Music Festival', 'Enjoy live music performances', '2025-06-15 18:00:00', 'City Park', 'default-event.jpg', 500, 1);
    ");

    echo "Database seeded successfully!";
} catch (PDOException $e) {
    die("Error seeding database: " . $e->getMessage());
}
