<?php
$dbPath = __DIR__ . '/classic_transport_db.sqlite';

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Initialize tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS parents (
        parent_id INTEGER PRIMARY KEY AUTOINCREMENT,
        full_name VARCHAR(150) NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(100),
        address VARCHAR(150)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        fullname VARCHAR(150) NOT NULL,
        class VARCHAR(50),
        parent_id INTEGER,
        uses_transport BOOLEAN DEFAULT 0,
        FOREIGN KEY (parent_id) REFERENCES parents (parent_id) ON DELETE SET NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS bus_routes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        route_name VARCHAR(100) NOT NULL,
        driver_name VARCHAR(100),
        monthly_fee DECIMAL(10,2) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS transport_enrollments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER,
        route_id INTEGER,
        amount_paid DECIMAL(10,2) DEFAULT 0,
        payment_date DATE,
        term VARCHAR(20),
        status VARCHAR(50),
        FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
        FOREIGN KEY (route_id) REFERENCES bus_routes (id) ON DELETE SET NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS payments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        enrollment_id INTEGER,
        amount DECIMAL(10,2),
        payment_date DATE,
        payment_method VARCHAR(50),
        FOREIGN KEY (enrollment_id) REFERENCES transport_enrollments (id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS support_messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        fullname VARCHAR(150),
        email VARCHAR(100),
        subject VARCHAR(150),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(20) DEFAULT 'New'
    )");

    // Add default admin user if it doesn't exist (password is 'admin123')
    $checkUser = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $checkUser->execute();
    if ($checkUser->fetchColumn() == 0) {
        $hashedPass = password_hash('admin123', PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)")
            ->execute(['admin', $hashedPass, 'System Administrator']);
    }

} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
