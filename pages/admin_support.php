<?php
require_once 'backend/db.php';

// Handle delete or status update
if (isset($_GET['action'])) {
    $id = $_GET['id'] ?? 0;
    if ($id) {
        try {
            if ($_GET['action'] === 'delete') {
                $pdo->prepare("DELETE FROM support_messages WHERE id = ?")->execute([$id]);
            } elseif ($_GET['action'] === 'mark_read') {
                $pdo->prepare("UPDATE support_messages SET status = 'Read' WHERE id = ?")->execute([$id]);
            }
        } catch (PDOException $e) {
            // Handle error silently or show a message
        }
    }
    // Redirect to clear the query params
    header("Location: ?page=admin_support");
    exit;
}

// Fetch all messages
$stmt = $pdo->query("SELECT * FROM support_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>

<div class="page-header">
    <div class="page-title-group">
        <h1 class="page-title">Support Messages</h1>
        <p class="page-subtitle">Manage and respond to student and parent inquiries.</p>
    </div>
</div>

<div class="table-container" style="margin-top: 2rem;">
    <div class="table-actions" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="font-weight: 600; color: #1F2937;">
            Total Messages: <?php echo count($messages); ?>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sender</th>
                <th>Subject</th>
                <th>Message Snippet</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($messages)): ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #6B7280;">No support messages yet.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                <tr>
                    <td style="white-space: nowrap;"><?php echo date('M d, H:i', strtotime($msg['created_at'])); ?></td>
                    <td>
                        <div style="font-weight: 500;"><?php echo htmlspecialchars($msg['fullname']); ?></div>
                        <div style="font-size: 0.8rem; color: #6B7280;"><?php echo htmlspecialchars($msg['email']); ?></div>
                    </td>
                    <td><span class="badge" style="background: rgba(230, 49, 151, 0.1); color: #E63197; border: none;"><?php echo htmlspecialchars($msg['subject']); ?></span></td>
                    <td title="<?php echo htmlspecialchars($msg['message']); ?>">
                        <?php echo htmlspecialchars(strlen($msg['message']) > 50 ? substr($msg['message'], 0, 47) . '...' : $msg['message']); ?>
                    </td>
                    <td>
                        <?php 
                        $statusClass = $msg['status'] === 'New' ? 'status-active' : 'status-pending'; 
                        ?>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo $msg['status']; ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <button onclick="viewMessage(<?php echo htmlspecialchars(json_encode($msg)); ?>)" class="btn" style="padding: 0.5rem; background: #F3F4F6; color: #374151; border: none; border-radius: 6px; cursor: pointer;" title="View Full Message">
                                <i data-lucide="eye" size="16"></i>
                            </button>
                            <?php if ($msg['status'] === 'New'): ?>
                            <a href="?page=admin_support&action=mark_read&id=<?php echo $msg['id']; ?>" class="btn" style="padding: 0.5rem; background: #DBEAFE; color: #1E40AF; border: none; border-radius: 6px; cursor: pointer;" title="Mark as Read">
                                <i data-lucide="check-check" size="16"></i>
                            </a>
                            <?php endif; ?>
                            <a href="?page=admin_support&action=delete&id=<?php echo $msg['id']; ?>" class="btn" style="padding: 0.5rem; background: #FEE2E2; color: #991B1B; border: none; border-radius: 6px; cursor: pointer;" onclick="return confirm('Are you sure you want to delete this message?')" title="Delete">
                                <i data-lucide="trash-2" size="16"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for viewing full message -->
<div id="message-modal" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: none; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 600px; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
            <div>
                <h2 id="modal-subject" style="font-size: 1.25rem; font-weight: 700; color: #1F2937;"></h2>
                <p id="modal-date" style="font-size: 0.875rem; color: #6B7280;"></p>
            </div>
            <button onclick="document.getElementById('message-modal').style.display='none'" style="background: none; border: none; cursor: pointer; color: #9CA3AF;"><i data-lucide="x" size="24"></i></button>
        </div>
        
        <div style="background: #F9FAFB; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <p style="margin-bottom: 0.5rem;"><strong style="font-size: 0.875rem; color: #374151;">From:</strong> <span id="modal-from" style="color: #1F2937;"></span></p>
            <p><strong style="font-size: 0.875rem; color: #374151;">Email:</strong> <span id="modal-email" style="color: #E63197;"></span></p>
        </div>
        
        <div style="line-height: 1.6; color: #4B5563; min-height: 150px; white-space: pre-wrap;" id="modal-body"></div>
        
        <div style="margin-top: 2rem; text-align: right;">
            <button onclick="document.getElementById('message-modal').style.display='none'" class="btn" style="padding: 0.75rem 1.5rem; background: #1F2937; color: white; border-radius: 8px; font-weight: 500; cursor: pointer; border: none;">Close</button>
        </div>
    </div>
</div>

<script>
    function viewMessage(msg) {
        document.getElementById('modal-subject').textContent = msg.subject;
        document.getElementById('modal-from').textContent = msg.fullname;
        document.getElementById('modal-email').textContent = msg.email;
        document.getElementById('modal-date').textContent = new Date(msg.created_at).toLocaleString();
        document.getElementById('modal-body').textContent = msg.message;
        
        const modal = document.getElementById('message-modal');
        modal.style.display = 'flex';
        
        // Mark as read if it is new
        if (msg.status === 'New') {
            fetch('?page=admin_support&action=mark_read&id=' + msg.id);
        }
    }
</script>
