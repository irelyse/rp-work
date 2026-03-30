<?php
require_once 'backend/db.php';

// Fetch all payments with their related enrollment and student info
$sql = "SELECT p.*, s.fullname as student_name, r.route_name
        FROM payments p
        JOIN transport_enrollments te ON p.enrollment_id = te.id
        JOIN students s ON te.student_id = s.id
        JOIN bus_routes r ON te.route_id = r.id
        ORDER BY p.id DESC";
$payments = $pdo->query($sql)->fetchAll();
?>

<div class="content-header" style="justify-content: flex-end; margin-top: -30px; margin-bottom: 20px;">
    <div class="header-tools">
        <button class="btn btn-secondary" onclick="alert('Generate Report feature is coming soon.')">
            <i data-lucide="download" style="margin-right: 8px;"></i>
            Export PDF Report
        </button>
        <button class="btn btn-primary" onclick="openAddModal('payments', ['enrollment_id', 'amount', 'payment_date', 'payment_method'])">
            <i data-lucide="plus" style="margin-right: 8px;"></i>
            New Transaction
        </button>
    </div>
</div>

<div class="table-container">
    <div class="table-title">Recent Transactions</div>
    <table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Student</th>
                <th>Route</th>
                <th>Amount Paid</th>
                <th>Date Paid</th>
                <th>Payment Method</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($payments)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b; font-style: italic;">
                        No payments found in history.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($payments as $pay): ?>
                    <tr>
                        <td><strong>TXN-<?php echo $pay['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($pay['student_name']); ?></td>
                        <td><small><?php echo htmlspecialchars($pay['route_name']); ?></small></td>
                        <td><span style="font-weight: 700; color: #059669;"><?php echo number_format($pay['amount']); ?> <span style="font-size: 0.7em; opacity: 0.6;">RWF</span></span></td>
                        <td><?php echo date('M d, Y', strtotime($pay['payment_date'])); ?></td>
                        <td>
                            <span class="badge badge-paid">
                                <i data-lucide="check" size="12" style="margin-right: 4px;"></i>
                                <?php echo htmlspecialchars($pay['payment_method'] ?: 'Cash'); ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-icon" title="View Receipt"><i data-lucide="printer"></i></button>
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
