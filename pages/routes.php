<?php
require_once 'backend/db.php';

// Fetch all bus routes and count their enrolled students
$sql = "SELECT r.*, (SELECT COUNT(*) FROM transport_enrollments te WHERE te.route_id = r.id) as student_count
        FROM bus_routes r
        ORDER BY r.id DESC";
$routes = $pdo->query($sql)->fetchAll();
?>

<div class="content-header" style="justify-content: flex-end; margin-top: -30px; margin-bottom: 20px;">
    <div class="header-tools">
        <button class="btn btn-primary" onclick="openAddModal('routes', ['route_name', 'driver_name', 'monthly_fee', 'total_pupils', 'revenue_potential'])">
            <i data-lucide="plus" style="margin-right: 8px;"></i>
            Create New Route
        </button>
    </div>
</div>

<div class="table-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="table-title">Bus Routes</div>
        <div style="position: relative;">
            <input type="text" placeholder="Filter routes, drivers..." class="local-table-filter" style="padding: 10px 15px 10px 35px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.85rem; width: 220px; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
            <i data-lucide="search" size="16" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Route Name</th>
                <th>Driver Name</th>
                <th>Monthly Fee</th>
                <th>Total Pupils</th>
                <th>Revenue Potential</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($routes)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b; font-style: italic;">
                        No transport routes defined yet.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($routes as $r): ?>
                    <tr>
                        <td><strong>ROUTE-<?php echo $r['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($r['route_name']); ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                    <i data-lucide="user" size="14"></i>
                                </div>
                                <?php echo htmlspecialchars($r['driver_name'] ?: 'No Driver Assigned'); ?>
                            </div>
                        </td>
                        <td><span style="font-weight: 600; color: #1e293b;"><?php echo number_format($r['monthly_fee']); ?> <span style="font-size: 0.7em; opacity: 0.6;">RWF</span></span></td>
                        <td>
                            <span class="badge badge-paid"><?php echo $r['student_count']; ?> Registered</span>
                        </td>
                        <td>
                            <?php echo number_format($r['student_count'] * $r['monthly_fee']); ?> <span style="font-size: 0.7em; opacity: 0.6;">RWF</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button class="btn btn-icon" title="View Route Logistics"><i data-lucide="map"></i></button>
                                <button class="btn btn-icon" title="Edit Route" onclick='openEditModal("routes", <?php echo $r["id"]; ?>, <?php echo json_encode($r); ?>)'><i data-lucide="settings-2"></i></button>
                                <button class="btn btn-icon text-red" title="Delete Route" onclick="deleteItem('routes', <?php echo $r['id']; ?>)"><i data-lucide="trash-2"></i></button>
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
