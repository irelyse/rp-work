<?php
require_once 'backend/db.php';

// Prepare data for reports
$totalRevenue = (float)$pdo->query("SELECT SUM(amount) FROM payments")->fetchColumn() ?: 0;
$pendingRevenue = (float)$pdo->query("SELECT SUM(r.monthly_fee - te.amount_paid) FROM transport_enrollments te JOIN bus_routes r ON te.route_id = r.id")->fetchColumn() ?: 0;
$totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$enrolledStudents = (int)$pdo->query("SELECT COUNT(*) FROM transport_enrollments")->fetchColumn();

// Fetch Detailed Financial Report
$reportSql = "SELECT te.id as enrollment_id, s.fullname as student_name, p.full_name as parent_name, r.route_name, r.monthly_fee, te.amount_paid, (r.monthly_fee - te.amount_paid) as balance, te.term, te.status 
              FROM transport_enrollments te 
              JOIN students s ON te.student_id = s.id 
              JOIN parents p ON s.parent_id = p.parent_id 
              JOIN bus_routes r ON te.route_id = r.id
              ORDER BY balance DESC";
$reportData = $pdo->query($reportSql)->fetchAll();

?>

<div class="content-header" style="justify-content: flex-end; margin-top: -30px; margin-bottom: 20px;">
    <div class="header-tools">
        <button class="btn btn-secondary" onclick="window.print()">
            <i data-lucide="printer" style="margin-right: 8px;"></i>
            Print Summary
        </button>
        <button class="btn btn-primary" onclick="showToast('Exporting Excel Report...', 'primary')">
            <i data-lucide="file-text" style="margin-right: 8px;"></i>
            Export CSV
        </button>
    </div>
</div>

<div class="stats-row" style="margin-bottom: 30px;">
    <div class="stat-card">
        <div class="stat-label">Total Revenue Collected</div>
        <div class="stat-value"><?php echo number_format($totalRevenue); ?> <span class="stat-currency">RWF</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Outstanding Balances</div>
        <div class="stat-value" style="color: #ef4444;"><?php echo number_format($pendingRevenue); ?> <span class="stat-currency">RWF</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Enrolled Students</div>
        <div class="stat-value"><?php echo $enrolledStudents; ?> <span style="font-size: 0.8rem; color: var(--text-dim);">/ <?php echo $totalStudents; ?> Total</span></div>
    </div>
</div>

<div class="table-container" style="margin-bottom: 30px;">
    <div class="table-title">Available Report Types</div>
    <table>
        <thead>
            <tr>
                <th>Report Name</th>
                <th>Description</th>
                <th>Frequency</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Monthly Revenue Report</strong></td>
                <td>Detailed breakdown of all fees collected this month.</td>
                <td><span class="badge badge-paid">Monthly</span></td>
                <td><button class="btn btn-icon" onclick="showToast('Generating...', 'primary')"><i data-lucide="download"></i></button></td>
            </tr>
            <tr>
                <td><strong>Student Enrollment List</strong></td>
                <td>List of all students currently using school transport.</td>
                <td><span class="badge badge-paid">Real-time</span></td>
                <td><button class="btn btn-icon" onclick="showToast('Generating...', 'primary')"><i data-lucide="download"></i></button></td>
            </tr>
            <tr>
                <td><strong>Outstanding Dues Report</strong></td>
                <td>Summary of parents with unpaid transport fees.</td>
                <td><span class="badge badge-pending">Weekly</span></td>
                <td><button class="btn btn-icon" onclick="showToast('Generating...', 'primary')"><i data-lucide="download"></i></button></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="table-container">
    <div class="table-title">Financial Balance Summary (Real-time)</div>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Parent</th>
                <th>Route</th>
                <th>Fee</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reportData)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-dim);">No financial data available yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reportData as $row): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['student_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['parent_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['route_name']); ?></td>
                        <td><?php echo number_format($row['monthly_fee']); ?></td>
                        <td><?php echo number_format($row['amount_paid']); ?></td>
                        <td style="color: <?php echo $row['balance'] > 0 ? '#ef4444' : '#10b981'; ?>; font-weight: 600;">
                            <?php echo number_format($row['balance']); ?>
                        </td>
                        <td>
                            <?php 
                                $statusClass = 'badge-paid';
                                if ($row['balance'] > 0 && $row['amount_paid'] > 0) $statusClass = 'badge-pending';
                                if ($row['balance'] == $row['monthly_fee']) $statusClass = 'badge-pending'; // Should be 'Unpaid' but using pending for color
                            ?>
                            <span class="badge <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span>
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
