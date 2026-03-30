<?php
require_once 'backend/db.php';

try {
    // Start transaction to ensure everything is deleted correctly
    $pdo->beginTransaction();

    // Tables to clear in order due to foreign key constraints
    $tables = [
        'payments',
        'transport_enrollments',
        'students',
        'bus_routes',
        'parents'
    ];

    foreach ($tables as $table) {
        $pdo->exec("DELETE FROM $table");
        // Reset sqlite autoincrement ID
        $pdo->exec("DELETE FROM sqlite_sequence WHERE name='$table'");
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'All data has been cleared from the database, but user accounts and structure were preserved!']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
