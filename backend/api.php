<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'db.php';

// Simple Router supporting api.php/resource or api.php?endpoint=resource
$requestMethod = $_SERVER['REQUEST_METHOD'];

$pathInfo = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : (isset($_GET['endpoint']) ? $_GET['endpoint'] : '');
$parts = explode('/', $pathInfo);
$resource = $parts[0] ?? '';
$id = $parts[1] ?? (isset($_GET['id']) ? $_GET['id'] : null);

// Get JSON Input
$input = json_decode(file_get_contents('php://input'), true);
error_log("RECEIVED PUT/POST REQUEST payload: " . print_r($input, true));

function respond($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function error($message, $status = 400) {
    respond(['error' => $message], $status);
}

switch ($resource) {
    case 'parents':
        if ($requestMethod === 'POST') {
            $pdo->beginTransaction();
            try {
                $sql = 'INSERT INTO parents (full_name, phone, email, address) VALUES (?, ?, ?, ?)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$input['full_name'] ?? '', $input['phone'] ?? '', $input['email'] ?? '', $input['address'] ?? '']);
                $parentId = $pdo->lastInsertId();
                
                if (!empty($input['students'])) {
                    $studentNames = explode(',', $input['students']);
                    $stmtStudent = $pdo->prepare('INSERT INTO students (fullname, parent_id) VALUES (?, ?)');
                    foreach ($studentNames as $name) {
                        $name = trim($name);
                        if (!empty($name)) {
                            $stmtStudent->execute([$name, $parentId]);
                        }
                    }
                }
                
                $pdo->commit();
                respond(['id' => $parentId, 'message' => 'Parent registered successfully'], 201);
            } catch (Exception $e) {
                $pdo->rollBack();
                error($e->getMessage());
            }
        } elseif ($requestMethod === 'GET') {
            if ($id) {
                $stmt = $pdo->prepare('SELECT * FROM parents WHERE parent_id = ?');
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) error('Parent not found', 404);
                respond($row);
            } else {
                $stmt = $pdo->query('SELECT * FROM parents');
                respond($stmt->fetchAll());
            }
        } elseif ($requestMethod === 'PUT') {
            $sql = 'UPDATE parents SET full_name = ?, phone = ?, email = ?, address = ? WHERE parent_id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['full_name'], $input['phone'], $input['email'], $input['address'], $id]);
            respond(['message' => 'Parent updated', 'changes' => $stmt->rowCount()]);
        } elseif ($requestMethod === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM parents WHERE parent_id = ?');
            $stmt->execute([$id]);
            respond(['message' => 'Parent deleted', 'changes' => $stmt->rowCount()]);
        }
        break;

    case 'students':
        if ($requestMethod === 'POST') {
            $sql = 'INSERT INTO students (fullname, class, parent_id, uses_transport) VALUES (?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['fullname'], $input['class'], $input['parent_id'], (isset($input['uses_transport']) && $input['uses_transport']) ? 1 : 0]);
            respond(['id' => $pdo->lastInsertId(), 'message' => 'Student registered successfully'], 201);
        } elseif ($requestMethod === 'GET') {
            if ($id) {
                $sql = 'SELECT s.*, p.full_name as parent_name, p.phone as parent_phone FROM students s LEFT JOIN parents p ON s.parent_id = p.parent_id WHERE s.id = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id]);
                $row = $stmt->fetch();
                if (!$row) error('Student not found', 404);
                respond($row);
            } else {
                $sql = 'SELECT s.*, p.full_name as parent_name FROM students s LEFT JOIN parents p ON s.parent_id = p.parent_id';
                $stmt = $pdo->query($sql);
                respond($stmt->fetchAll());
            }
        } elseif ($requestMethod === 'PUT') {
            $sql = 'UPDATE students SET fullname = ?, class = ?, parent_id = ?, uses_transport = ? WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['fullname'], $input['class'], $input['parent_id'], (isset($input['uses_transport']) && $input['uses_transport']) ? 1 : 0, $id]);
            respond(['message' => 'Student updated', 'changes' => $stmt->rowCount()]);
        } elseif ($requestMethod === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
            $stmt->execute([$id]);
            respond(['message' => 'Student deleted', 'changes' => $stmt->rowCount()]);
        }
        break;

    case 'routes':
        if ($requestMethod === 'POST') {
            $sql = 'INSERT INTO bus_routes (route_name, driver_name, monthly_fee) VALUES (?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['route_name'], $input['driver_name'], $input['monthly_fee']]);
            respond(['id' => $pdo->lastInsertId(), 'message' => 'Route created successfully'], 201);
        } elseif ($requestMethod === 'GET') {
            $stmt = $pdo->query('SELECT * FROM bus_routes');
            respond($stmt->fetchAll());
        } elseif ($requestMethod === 'PUT') {
            $sql = 'UPDATE bus_routes SET route_name = ?, driver_name = ?, monthly_fee = ? WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['route_name'], $input['driver_name'], $input['monthly_fee'], $id]);
            respond(['message' => 'Route updated', 'changes' => $stmt->rowCount()]);
        } elseif ($requestMethod === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM bus_routes WHERE id = ?');
            $stmt->execute([$id]);
            respond(['message' => 'Route deleted', 'changes' => $stmt->rowCount()]);
        }
        break;

    case 'enrollments':
        if ($requestMethod === 'POST') {
            $sql = 'INSERT INTO transport_enrollments (student_id, route_id, term, status) VALUES (?, ?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['student_id'], $input['route_id'], $input['term'], 'Pending']);
            respond(['id' => $pdo->lastInsertId(), 'message' => 'Student enrolled in route'], 201);
        } elseif ($requestMethod === 'GET') {
            $sql = 'SELECT te.*, s.parent_id, s.fullname as student_name, p.full_name as parent_name, r.route_name, r.monthly_fee FROM transport_enrollments te JOIN students s ON te.student_id = s.id LEFT JOIN parents p ON s.parent_id = p.parent_id JOIN bus_routes r ON te.route_id = r.id';
            $stmt = $pdo->query($sql);
            respond($stmt->fetchAll());
        } elseif ($requestMethod === 'PUT') {
            $sql = 'UPDATE transport_enrollments SET student_id = ?, route_id = ?, term = ?, status = ? WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['student_id'], $input['route_id'], $input['term'], $input['status'], $id]);
            respond(['message' => 'Enrollment updated', 'changes' => $stmt->rowCount()]);
        } elseif ($requestMethod === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM transport_enrollments WHERE id = ?');
            $stmt->execute([$id]);
            respond(['message' => 'Enrollment deleted', 'changes' => $stmt->rowCount()]);
        }
        break;

    case 'payments':
        if ($requestMethod === 'POST') {
            $pdo->beginTransaction();
            try {
                $sqlPayment = 'INSERT INTO payments (enrollment_id, amount, payment_date, payment_method) VALUES (?, ?, ?, ?)';
                $stmtPayment = $pdo->prepare($sqlPayment);
                $stmtPayment->execute([$input['enrollment_id'], $input['amount'], $input['payment_date'], $input['payment_method']]);

                $sqlUpdate = "UPDATE transport_enrollments 
                              SET amount_paid = amount_paid + ?, 
                                  payment_date = ?,
                                  status = CASE 
                                      WHEN (amount_paid + ?) >= (SELECT monthly_fee FROM bus_routes WHERE id = transport_enrollments.route_id) THEN 'Paid'
                                      ELSE 'Partial'
                                  END
                              WHERE id = ?";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->execute([$input['amount'], $input['payment_date'], $input['amount'], $input['enrollment_id']]);

                $pdo->commit();
                respond(['message' => 'Payment recorded successfully'], 201);
            } catch (Exception $e) {
                $pdo->rollBack();
                error($e->getMessage());
            }
        } elseif ($requestMethod === 'GET') {
            if ($pathInfo === 'payments/report') {
                $sql = "SELECT te.id as enrollment_id, s.fullname as student_name, p.full_name as parent_name, r.route_name, r.monthly_fee, te.amount_paid, (r.monthly_fee - te.amount_paid) as balance, te.term, te.status FROM transport_enrollments te JOIN students s ON te.student_id = s.id JOIN parents p ON s.parent_id = p.parent_id JOIN bus_routes r ON te.route_id = r.id";
                $stmt = $pdo->query($sql);
                respond($stmt->fetchAll());
            } else {
                $stmt = $pdo->query('SELECT * FROM payments');
                respond($stmt->fetchAll());
            }
        } elseif ($requestMethod === 'PUT') {
            $sql = 'UPDATE payments SET amount = ?, payment_date = ?, payment_method = ? WHERE id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$input['amount'], $input['payment_date'], $input['input_method'] ?? $input['payment_method'], $id]);
            respond(['message' => 'Payment updated', 'changes' => $stmt->rowCount()]);
        }
        break;

    case 'dashboard':
        if ($parts[1] === 'stats') {
            $stats = [];
            $stats['total_students'] = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
            $stats['transport_students'] = (int)$pdo->query('SELECT COUNT(*) FROM students WHERE uses_transport = 1')->fetchColumn();
            $stats['total_routes'] = (int)$pdo->query('SELECT COUNT(*) FROM bus_routes')->fetchColumn();
            $stats['active_routes'] = (int)$pdo->query('SELECT COUNT(DISTINCT route_id) FROM transport_enrollments')->fetchColumn();
            $stats['total_payments'] = (float)$pdo->query('SELECT SUM(amount_paid) FROM transport_enrollments')->fetchColumn() ?: 0;
            $stats['outstanding_balance'] = (float)$pdo->query('SELECT SUM(r.monthly_fee - te.amount_paid) FROM transport_enrollments te JOIN bus_routes r ON te.route_id = r.id')->fetchColumn() ?: 0;
            respond($stats);
        }
        break;
    
    case 'payment': // for payment/report
        if ($parts[1] === 'report') {
            $sql = "SELECT te.id as enrollment_id, s.fullname as student_name, p.full_name as parent_name, r.route_name, r.monthly_fee, te.amount_paid, (r.monthly_fee - te.amount_paid) as balance, te.term, te.status FROM transport_enrollments te JOIN students s ON te.student_id = s.id JOIN parents p ON s.parent_id = p.parent_id JOIN bus_routes r ON te.route_id = r.id";
            $stmt = $pdo->query($sql);
            respond($stmt->fetchAll());
        }
        break;

    default:
        error('Endpoint not found', 404);
}
