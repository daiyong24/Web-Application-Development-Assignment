<?php
require __DIR__.'/includes/auth.php';
require __DIR__.'/../includes/db.php';
require __DIR__.'/includes/csrf.php';
include __DIR__.'/includes/header.php';

$msg = '';

// Update read status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_status') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $is_read = (int)($_POST['is_read'] ?? 0);
    
    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = ? WHERE id = ?");
        $stmt->execute([$is_read, $id]);
        $msg = "Message #$id updated.";
    }
}

// Delete message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_message') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Message #$id deleted.";
    }
}

// Filters
$where = []; 
$params = [];
if (!empty($_GET['q'])) { 
    $where[] = "(name LIKE ? OR email LIKE ? OR purpose LIKE ? OR message LIKE ?)"; 
    $q = '%' . $_GET['q'] . '%'; 
    $params = [$q, $q, $q, $q]; 
}
if (!empty($_GET['read_status'])) { 
    $where[] = "is_read = ?"; 
    $params[] = ($_GET['read_status'] === 'read' ? 1 : 0); 
}

// Get messages count for stats
$unread_count = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
$total_count = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

// Get messages
$sql = "SELECT * FROM contact_messages";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY created_at DESC LIMIT 100";
$stmt = $pdo->prepare($sql); 
$stmt->execute($params);
$messages = $stmt->fetchAll();

// Detail view
$detail = null;
if (!empty($_GET['id'])) {
    $d = $pdo->prepare("SELECT * FROM contact_messages WHERE id = ? LIMIT 1");
    $d->execute([(int)$_GET['id']]); 
    $detail = $d->fetch();
    
    // Mark as read when viewing
    if ($detail && !$detail['is_read']) {
        $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$detail['id']]);
        $detail['is_read'] = 1;
    }
}
?>
<div class="card">
    <h2>Contact Messages</h2>
    
    <?php if($msg): ?>
        <div class="card" style="background:#0a0f1c"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    
    <div class="stats">
        <div class="stat">
            <span class="stat-number"><?= $total_count ?></span>
            <span class="stat-label">Total Messages</span>
        </div>
        <div class="stat">
            <span class="stat-number"><?= $unread_count ?></span>
            <span class="stat-label">Unread Messages</span>
        </div>
    </div>
    
    <form method="get" class="row">
        <input name="q" placeholder="Search name, email, purpose or message…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <select name="read_status">
            <option value="">All messages</option>
            <option value="unread" <?= (($_GET['read_status'] ?? '') === 'unread' ? 'selected' : '') ?>>Unread only</option>
            <option value="read" <?= (($_GET['read_status'] ?? '') === 'read' ? 'selected' : '') ?>>Read only</option>
        </select>
        <button class="btn" type="submit">Filter</button>
    </form>
</div>

<?php if($detail): ?>
<div class="card">
    <h3>Message #<?= (int)$detail['id'] ?></h3>
    
    <div class="message-detail">
        <div class="detail-row">
            <strong>Name:</strong> <?= htmlspecialchars($detail['name']) ?>
        </div>
        <div class="detail-row">
            <strong>Contact Number:</strong> <?= htmlspecialchars($detail['contact_number']) ?>
        </div>
        <div class="detail-row">
            <strong>Email:</strong> <?= htmlspecialchars($detail['email']) ?>
        </div>
        <div class="detail-row">
            <strong>Purpose:</strong> <?= htmlspecialchars($detail['purpose']) ?>
        </div>
        <div class="detail-row">
            <strong>Message:</strong><br>
            <div class="message-content"><?= nl2br(htmlspecialchars($detail['message'])) ?></div>
        </div>
        <div class="detail-row">
            <strong>Received:</strong> <?= date('M j, Y g:i A', strtotime($detail['created_at'])) ?>
        </div>
        <div class="detail-row">
            <strong>Status:</strong> 
            <span class="badge <?= $detail['is_read'] ? 'badge-read' : 'badge-unread' ?>">
                <?= $detail['is_read'] ? 'Read' : 'Unread' ?>
            </span>
        </div>
    </div>
    
    <form method="post" class="row">
        <?php csrf_field(); ?>
        <input type="hidden" name="action" value="update_status">
        <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
        <select name="is_read">
            <option value="0" <?= !$detail['is_read'] ? 'selected' : '' ?>>Mark as Unread</option>
            <option value="1" <?= $detail['is_read'] ? 'selected' : '' ?>>Mark as Read</option>
        </select>
        <button class="btn" type="submit">Update Status</button>
    </form>
    
    <form method="post" class="row" onsubmit="return confirm('Are you sure you want to delete this message?');">
        <?php csrf_field(); ?>
        <input type="hidden" name="action" value="delete_message">
        <input type="hidden" name="id" value="<?= (int)$detail['id'] ?>">
        <button class="btn secondary" type="submit" style="background-color: #f44336;">Delete Message</button>
    </form>
</div>
<?php endif; ?>

<div class="card">
    <h3>Latest 100 Messages</h3>
    
    <?php if (empty($messages)): ?>
        <p>No messages found.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($messages as $message): ?>
                <tr class="<?= $message['is_read'] ? '' : 'unread-row' ?>">
                    <td>#<?= (int)$message['id'] ?></td>
                    <td><?= date('M j, Y', strtotime($message['created_at'])) ?></td>
                    <td><?= htmlspecialchars($message['name']) ?></td>
                    <td><?= htmlspecialchars($message['email']) ?></td>
                    <td><?= htmlspecialchars($message['purpose']) ?></td>
                    <td>
                        <span class="badge <?= $message['is_read'] ? 'badge-read' : 'badge-unread' ?>">
                            <?= $message['is_read'] ? 'Read' : 'Unread' ?>
                        </span>
                    </td>
                    <td>
                        <a class="btn secondary" href="?id=<?= (int)$message['id'] ?>">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
.stats {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.stat {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    min-width: 120px;
}

.stat-number {
    display: block;
    font-size: 24px;
    font-weight: bold;
    color: #3B82F6;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

.message-detail {
    margin-bottom: 20px;
}

.detail-row {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.message-content {
    background: #000000ff;
    padding: 15px;
    border-radius: 5px;
    margin-top: 5px;
}

.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
}

.badge-unread {
    background-color: #f44336;
    color: white;
}

.badge-read {
    background-color: #4CAF50;
    color: white;
}

.unread-row {
    background-color: #f9f9f9;
    font-weight: bold;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    font-weight: bold;
}
</style>

<?php include __DIR__.'/includes/footer.php'; ?>