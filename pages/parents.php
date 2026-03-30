<?php
require_once 'backend/db.php';

// Fetch all parents and count their students
$sql = "SELECT p.*, (SELECT COUNT(*) FROM students s WHERE s.parent_id = p.parent_id) as student_count
        FROM parents p
        ORDER BY p.parent_id DESC";
$parents = $pdo->query($sql)->fetchAll();
?>

<div class="content-header" style="justify-content: flex-end; margin-top: -30px; margin-bottom: 20px;">
    <div class="header-tools">
        <button class="btn btn-primary" onclick="openAddModal('parents', ['full_name', 'phone', 'email', 'address'])">
            <i data-lucide="user-plus" style="margin-right: 8px;"></i>
            Register New Parent
        </button>
    </div>
</div>

<div class="table-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="table-title">Parents Management</div>
        <div style="position: relative;">
            <input type="text" placeholder="Search by name, phone..." class="local-table-filter" style="padding: 10px 15px 10px 35px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.85rem; width: 220px; outline: none; transition: all 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
            <i data-lucide="search" size="16" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Students</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($parents)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">
                        <i data-lucide="users" size="48" style="opacity: 0.2; display: block; margin: 0 auto 10px;"></i>
                        No parents found. Start by registering a parent.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($parents as $p): ?>
                    <tr>
                        <td><strong>#<?php echo $p['parent_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($p['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($p['phone'] ?: 'No Phone'); ?></td>
                        <td><small><?php echo htmlspecialchars($p['email'] ?: 'No Email'); ?></small></td>
                        <td><?php echo htmlspecialchars($p['address'] ?: 'No Address'); ?></td>
                        <td>
                            <span class="badge <?php echo $p['student_count'] > 0 ? 'badge-paid' : 'badge-pending'; ?>">
                                <?php echo $p['student_count']; ?> Student(s)
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button class="btn btn-icon" title="Edit Contact" onclick='openEditModal("parents", <?php echo $p["parent_id"]; ?>, <?php echo json_encode($p); ?>)'><i data-lucide="edit-3"></i></button>
                                <button class="btn btn-icon text-red" title="Delete Account" onclick="deleteItem('parents', <?php echo $p['parent_id']; ?>)"><i data-lucide="user-minus"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Re-initialize icons since Lucide runs on load and this page might be included dynamically 
    // depending on how the frontend routing is done. 
    // In index.php, it's just included, so it should be fine.
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
