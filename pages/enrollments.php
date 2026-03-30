<?php
require_once 'backend/db.php';

// Fetch all transport enrollments with student and route details
$sql = "SELECT te.*, s.fullname as student_name, r.route_name, r.monthly_fee
        FROM transport_enrollments te
        JOIN students s ON te.student_id = s.id
        JOIN bus_routes r ON te.route_id = r.id
        ORDER BY te.id DESC";
$enrollments = $pdo->query($sql)->fetchAll();
?>

<div class="content-header" style="justify-content: flex-end; margin-top: -30px; margin-bottom: 20px;">
    <div class="header-tools">
        <button class="btn btn-primary" onclick="openAddModal('enrollments', ['student_id', 'route_id', 'term'])">
            <i data-lucide="plus" style="margin-right: 8px;"></i>
            New Enrollment
        </button>
    </div>
</div>

<div class="table-container">
    <div class="table-title">Active Enrollments</div>
    <table>
        <thead>
            <tr>
                <th>Enrollment ID</th>
                <th>Student</th>
                <th>Route Name</th>
                <th>Monthly Fee</th>
                <th>Term</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($enrollments)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b; font-style: italic;">
                        No students enrolled in any bus routes.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($enrollments as $e): ?>
                    <tr>
                        <td><strong>ENR-<?php echo $e['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($e['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($e['route_name']); ?></td>
                        <td><span style="font-weight: 600; color: #1e293b;"><?php echo number_format($e['monthly_fee']); ?> <span style="font-size: 0.7em; opacity: 0.6;">RWF</span></span></td>
                        <td><?php echo htmlspecialchars($e['term'] ?: 'Term 1'); ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($e['status']); ?>">
                                <i data-lucide="<?php echo $e['status'] === 'Paid' ? 'check-circle' : 'alert-circle'; ?>" size="12" style="margin-right: 4px;"></i>
                                <?php echo htmlspecialchars($e['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button class="btn btn-icon" title="Edit Enrollment" onclick='openEditModal("enrollments", <?php echo $e["id"]; ?>, <?php echo json_encode($e); ?>)'><i data-lucide="edit-3"></i></button>
                                <button class="btn btn-icon text-red" title="Unenroll Student" onclick="deleteItem('enrollments', <?php echo $e['id']; ?>)"><i data-lucide="trash-2"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
