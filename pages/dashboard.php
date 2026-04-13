<?php
require_once 'backend/db.php';

// Fetch stats from database
$totalStudents = (int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$transportStudents = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE uses_transport = 1")->fetchColumn();
$totalRoutes = (int)$pdo->query("SELECT COUNT(*) FROM bus_routes")->fetchColumn();
$activeRoutes = (int)$pdo->query("SELECT COUNT(DISTINCT route_id) FROM transport_enrollments")->fetchColumn();
$totalPayments = (float)$pdo->query("SELECT SUM(amount_paid) FROM transport_enrollments")->fetchColumn() ?: 0;
$outstandingBalance = (float)$pdo->query("SELECT SUM(r.monthly_fee - te.amount_paid) FROM transport_enrollments te JOIN bus_routes r ON te.route_id = r.id")->fetchColumn() ?: 0;
$newMessages = (int)$pdo->query("SELECT COUNT(*) FROM support_messages WHERE status = 'New'")->fetchColumn();
$totalReports = (int)$pdo->query("SELECT COUNT(*) FROM payments")->fetchColumn();

$stats = [
    'total_students' => $totalStudents,
    'transport_students' => $transportStudents,
    'total_routes' => $totalRoutes,
    'active_routes' => $activeRoutes,
    'total_payments' => $totalPayments,
    'outstanding_balance' => $outstandingBalance,
    'new_messages' => $newMessages,
    'total_reports' => $totalReports
];

// Fetch recent payments (enrollments with their related info)
$sql = "SELECT s.fullname as student_name, p.full_name as parent_name, te.amount_paid, te.status
        FROM transport_enrollments te
        JOIN students s ON te.student_id = s.id
        JOIN parents p ON s.parent_id = p.parent_id
        ORDER BY te.id DESC
        LIMIT 5";
$recentPayments = $pdo->query($sql)->fetchAll();
?>



<div class="stats-row">
    <div class="stat-card">
        <div class="stat-label">
            <span style="display: flex; align-items: center; gap: 8px;">
                <div style="background: rgba(230, 49, 151, 0.1); padding: 8px; border-radius: 10px;">
                    <i data-lucide="users" size="18" color="#E63197"></i>
                </div>
                Total Students
            </span>
            <i data-lucide="more-vertical" size="16"></i>
        </div>
        <div class="stat-value" style="display: flex; flex-direction: column; align-items: flex-start;">
            <div style="display: flex; width: 100%; align-items: center;">
                <?php echo $stats['total_students']; ?>
                <span style="font-size: 0.75rem; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 4px 8px; border-radius: 20px; margin-left: auto;">All</span>
            </div>
            <span style="font-size: 0.85rem; color: var(--text-dim); margin-top: 8px; font-weight: 500;">
                <i data-lucide="bus" size="14" style="vertical-align: middle; margin-right: 4px;"></i>
                <?php echo $stats['transport_students']; ?> have transport
            </span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            <span style="display: flex; align-items: center; gap: 8px;">
                <div style="background: rgba(139, 92, 246, 0.1); padding: 8px; border-radius: 10px;">
                    <i data-lucide="map" size="18" color="#8b5cf6"></i>
                </div>
                Total Routes
            </span>
            <i data-lucide="more-vertical" size="16"></i>
        </div>
        <div class="stat-value" style="display: flex; flex-direction: column; align-items: flex-start;">
            <div style="display: flex; width: 100%; align-items: center;">
                <?php echo $stats['total_routes']; ?>
                <span style="font-size: 0.75rem; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 4px 8px; border-radius: 20px; margin-left: auto;">Total</span>
            </div>
            <span style="font-size: 0.85rem; color: var(--text-dim); margin-top: 8px; font-weight: 500;">
                <i data-lucide="navigation" size="14" style="vertical-align: middle; margin-right: 4px;"></i>
                <?php echo $stats['active_routes']; ?> active (with students)
            </span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            <span style="display: flex; align-items: center; gap: 8px;">
                <div style="background: rgba(16, 185, 129, 0.1); padding: 8px; border-radius: 10px;">
                    <i data-lucide="trending-up" size="18" color="#10b981"></i>
                </div>
                Total Income
            </span>
            <i data-lucide="more-vertical" size="16"></i>
        </div>
        <div class="stat-value" style="display: flex; flex-direction: column; align-items: flex-start;">
            <div style="display: flex; width: 100%; align-items: center;">
                <span style="font-size: 1.2rem; margin-right: 5px; color: var(--text-dim);">RWF</span>
                <?php echo number_format($stats['total_payments']); ?>
                <span style="font-size: 0.75rem; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 4px 8px; border-radius: 20px; margin-left: auto;">+12.8%</span>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            <span style="display: flex; align-items: center; gap: 8px;">
                <div style="background: rgba(239, 68, 68, 0.1); padding: 8px; border-radius: 10px;">
                    <i data-lucide="alert-circle" size="18" color="#ef4444"></i>
                </div>
                Outstanding
            </span>
            <i data-lucide="more-vertical" size="16"></i>
        </div>
        <div class="stat-value" style="display: flex; flex-direction: column; align-items: flex-start; color: #ef4444;">
            <div style="display: flex; width: 100%; align-items: center;">
                <span style="font-size: 1.2rem; margin-right: 5px; color: var(--text-dim);">RWF</span>
                <?php echo number_format($stats['outstanding_balance']); ?>
                <span style="font-size: 0.75rem; color: #ef4444; background: rgba(239, 68, 68, 0.1); padding: 4px 8px; border-radius: 20px; margin-left: auto;">Action Req</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">
            <span style="display: flex; align-items: center; gap: 8px;">
                <div style="background: rgba(230, 49, 151, 0.1); padding: 8px; border-radius: 10px;">
                    <i data-lucide="mail" size="18" color="#E63197"></i>
                </div>
                Support Messages
            </span>
            <i data-lucide="more-vertical" size="16"></i>
        </div>
        <div class="stat-value" style="display: flex; flex-direction: column; align-items: flex-start;">
            <div style="display: flex; width: 100%; align-items: center;">
                <?php echo $stats['new_messages']; ?>
                <span style="font-size: 0.75rem; color: <?php echo $stats['new_messages'] > 0 ? '#ef4444' : '#10b981'; ?>; background: <?php echo $stats['new_messages'] > 0 ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)'; ?>; padding: 4px 8px; border-radius: 20px; margin-left: auto;">
                    <?php echo $stats['new_messages'] > 0 ? 'New Arrivals' : 'No New'; ?>
                </span>
            </div>
            <a href="?page=admin_support" style="text-decoration: none; font-size: 0.85rem; color: var(--primary); margin-top: 8px; font-weight: 600;">View Inbox &rarr;</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            <span style="display: flex; align-items: center; gap: 8px;">
                <div style="background: rgba(59, 130, 246, 0.1); padding: 8px; border-radius: 10px;">
                    <i data-lucide="file-text" size="18" color="#3b82f6"></i>
                </div>
                Payment Reports
            </span>
            <i data-lucide="more-vertical" size="16"></i>
        </div>
        <div class="stat-value" style="display: flex; flex-direction: column; align-items: flex-start;">
            <div style="display: flex; width: 100%; align-items: center;">
                <?php echo $stats['total_reports']; ?>
                <span style="font-size: 0.75rem; color: #3b82f6; background: rgba(59, 130, 246, 0.1); padding: 4px 8px; border-radius: 20px; margin-left: auto;">Generated</span>
            </div>
            <a href="?page=reports" style="text-decoration: none; font-size: 0.85rem; color: #3b82f6; margin-top: 8px; font-weight: 600;">Open Center &rarr;</a>
        </div>
    </div>
</div>

<div class="table-container" style="background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 4px 30px rgba(0,0,0,0.02); margin-top: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <div class="table-title">Monthly Analytics</div>
            <p style="font-size: 0.85rem; color: #64748b; margin-top: 5px;">Revenue trends for the current academic year.</p>
        </div>
        <div style="display: flex; gap: 15px; font-size: 0.82rem; font-weight: 600;">
            <span style="display: flex; align-items: center; gap: 6px;"><div style="width: 10px; height: 10px; background: #E63197; border-radius: 2px;"></div> Income</span>
            <span style="display: flex; align-items: center; gap: 6px;"><div style="width: 10px; height: 10px; background: rgba(230, 49, 151, 0.2); border-radius: 2px;"></div> Outstanding</span>
        </div>
    </div>
    
    <div style="height: 350px; position: relative;">
        <canvas id="paymentsChart"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('paymentsChart').getContext('2d');
    
    // Gradient for the primary bars
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, '#E63197');
    gradient.addColorStop(1, '#FF80C5');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            datasets: [{
                label: 'Monthly Income',
                data: [32000, 38000, 31000, 42000, 30500, 48000, 32000, 28000],
                backgroundColor: gradient,
                borderRadius: 8,
                barThickness: 34,
                hoverBackgroundColor: '#d42085'
            }, {
                label: 'Outstanding',
                data: [8000, 12000, 9500, 15000, 7800, 21000, 10500, 13000],
                backgroundColor: 'rgba(230, 49, 151, 0.12)',
                borderRadius: 8,
                barThickness: 34,
                hoverBackgroundColor: 'rgba(230, 49, 151, 0.2)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1a1a1a',
                    bodyColor: '#4b5563',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' RWF';
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 12, weight: '500' } }
                }
            }
        }
    });
});
</script>

