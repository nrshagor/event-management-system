<?php

require_once(__DIR__ . '/db.php');

try {
    // Insert a default user to ensure the foreign key exists
    $pdo->exec("
        INSERT INTO users (id, username, email, password, role) VALUES 
        (1, 'Super Admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_BCRYPT) . "', 'super_admin'),
        (2, 'user1', 'user1@example.com', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 'user')
    ");

    // Insert sample events after user insertion
    $pdo->exec("
        INSERT INTO events (name, description, date, location, capacity, image, created_by) VALUES 
        ('Tech Conference', 'Annual tech meet', '2025-05-01 10:00:00', 'Conference Hall', 200, 'default-event.jpg', 1),
        ('Music Festival', 'Enjoy live music performances', '2025-06-15 18:00:00', 'City Park', 500, 'default-event.jpg', 1)
    ");

    echo "Database seeded successfully!";
} catch (PDOException $e) {
    die("Error seeding database: " . $e->getMessage());
}
