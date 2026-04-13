<?php
/**
 * Database Configuration
 * Update these details for InfinityFree Hosting
 */
$host = 'localhost'; // Use 'sqlXXX.infinityfree.com' from your InfinityFree Dashboard
$dbname = 'classic_transport_db'; 
$username = 'root'; // e.g. 'if0_380000'
$password = ''; // Your account password

/**
 * DATABASE MODE: 'mysql' for Hosting, 'sqlite' for Local Development
 */
$dbMode = 'sqlite'; // Change this to 'mysql' when uploading to InfinityFree

try {
    if ($dbMode === 'mysql') {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
    } else {
        // SQLite Mode (Local)
        $dbPath = __DIR__ . '/classic_transport_db.sqlite';
        $pdo = new PDO("sqlite:" . $dbPath);
    }
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Initial SQL to create tables if they don't exist
    // This is compatible with MySQL & SQLite syntax for primary keys
    $pk = ($dbMode === 'mysql') ? 'INT AUTO_INCREMENT PRIMARY KEY' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $timestamp = ($dbMode === 'mysql') ? 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP' : 'DATETIME DEFAULT CURRENT_TIMESTAMP';

    $pdo->exec("CREATE TABLE IF NOT EXISTS parents (
        parent_id $pk,
        full_name VARCHAR(150) NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(100),
        address VARCHAR(150)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id $pk,
        fullname VARCHAR(150) NOT NULL,
        class VARCHAR(50),
        parent_id INT,
        uses_transport TINYINT(1) DEFAULT 0
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS bus_routes (
        id $pk,
        route_name VARCHAR(100) NOT NULL,
        driver_name VARCHAR(100),
        monthly_fee DECIMAL(10,2) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS transport_enrollments (
        id $pk,
        student_id INT,
        route_id INT,
        amount_paid DECIMAL(10,2) DEFAULT 0,
        payment_date DATE,
        term VARCHAR(20),
        status VARCHAR(50)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS payments (
        id $pk,
        enrollment_id INT,
        amount DECIMAL(10,2),
        payment_date DATE,
        payment_method VARCHAR(50)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id $pk,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS support_messages (
        id $pk,
        fullname VARCHAR(150),
        email VARCHAR(100),
        subject VARCHAR(150),
        message TEXT,
        created_at $timestamp,
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
    die("Database Connection Error: " . $e->getMessage());
}
?>
