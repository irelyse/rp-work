-- CLASSIC ACADEMY TRANSPORT - MYSQL SETUP SCRIPT

CREATE TABLE IF NOT EXISTS parents (
    parent_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address VARCHAR(150)
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL,
    class VARCHAR(50),
    parent_id INT,
    uses_transport TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS bus_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(100) NOT NULL,
    driver_name VARCHAR(100),
    monthly_fee DECIMAL(10,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS transport_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    route_id INT,
    amount_paid DECIMAL(10,2) DEFAULT 0,
    payment_date DATE,
    term VARCHAR(20),
    status VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT,
    amount DECIMAL(10,2),
    payment_date DATE,
    payment_method VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS support_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150),
    email VARCHAR(100),
    subject VARCHAR(150),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'New'
);

-- Default Admin (password: admin123)
-- INSERT IGNORE INTO users (username, password, full_name) VALUES ('admin', '$2y$10$8.7.f.l.I.Z.g.Z/G.v.G.G.V.G.g.G.g.G.g.G.g.G.g.G.g.G.g.G.g.', 'System Administrator');
-- Note: The PHP backend will automatically create the admin user on first run if it's missing.
