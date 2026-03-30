<?php
require_once 'backend/db.php';

// Fetch all students with their parent information
$sql = "SELECT s.*, p.full_name as parent_name 
        FROM students s 
        LEFT JOIN parents p ON s.parent_id = p.parent_id";
$students = $pdo->query($sql)->fetchAll();
?>

<div class="content-header" style="justify-content: flex-end; margin-bottom: 30px;">
    <div class="header-tools">
        <button class="btn btn-primary" onclick="openAddModal('students', ['fullname', 'class', 'parent_id', 'uses_transport'])">
            <i data-lucide="plus" size="18" style="margin-right: 8px;"></i>
            Register New Student
        </button>
    </div>
</div>

<div class="table-container">
    <div class="table-title">All Students</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Class</th>
                <th>Parent Name</th>
                <th>Transport</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No students found in the database.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><?php echo $s['fullname']; ?></td>
                        <td><?php echo $s['class']; ?></td>
                        <td><?php echo $s['parent_name'] ?: 'None'; ?></td>
                        <td>
                            <span class="badge badge-<?php echo $s['uses_transport'] ? 'paid' : 'pending'; ?>">
                                <?php echo $s['uses_transport'] ? 'Yes' : 'No'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-icon" title="Edit" onclick='openEditModal("students", <?php echo $s["id"]; ?>, <?php echo json_encode($s); ?>)'><i data-lucide="edit-2"></i></button>
                            <button class="btn btn-icon text-red" title="Delete" onclick="deleteItem('students', <?php echo $s['id']; ?>)"><i data-lucide="trash-2"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
