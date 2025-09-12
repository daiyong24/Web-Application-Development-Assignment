<?php
/** @var array $bookings */
if (!function_exists('money_my')) {
  function money_my($v) {
    return 'RM ' . number_format((float)$v, 2);
  }
}


// tiny inline styles for status badges (or move to your CSS)
?>
<style>
  .history-toolbar{ display:flex; align-items:center; gap:12px; margin-bottom:12px; flex-wrap:wrap; }
  .history-toolbar .search input{ padding:8px 10px; border:1px solid #ddd; border-radius:8px; }
  .history-toolbar .page-size select{ padding:8px 10px; border:1px solid #ddd; border-radius:8px; }
  .pager button{ padding:8px 12px; border-radius:8px; border:0; background:#eee; cursor:pointer; }
  .pager button:disabled{ opacity:.5; cursor:not-allowed; }
  .status-badge{ padding:2px 10px; border-radius:12px; font-size:.85rem; border:1px solid #d1d5db; color:#374151; white-space:nowrap; }
  .status-pending{ border-color:#f59e0b; color:#92400e; background:#fffbeb; }
  .status-confirmed{ border-color:#3b82f6; color:#1e40af; background:#eff6ff; }
  .status-completed{ border-color:#10b981; color:#065f46; background:#ecfdf5; }
  .status-cancelled{ border-color:#ef4444; color:#991b1b; background:#fef2f2; }
  table.history{ width:100%; border-collapse:collapse; }
  table.history th, table.history td{ padding:10px 12px; border-bottom:1px solid #e5e7eb; text-align:left; }
  table.history th{ background:#f9fafb; }
</style>

<div class="history-toolbar">
  <div class="search"><input type="text" placeholder="Search in table…"></div>
  <div class="page-size">
    <label>Rows/page
      <select>
        <option>5</option>
        <option selected>10</option>
        <option>20</option>
        <option>50</option>
      </select>
    </label>
  </div>
  <div class="pager">
    <button class="prev">Prev</button>
    <button class="next">Next</button>
  </div>
</div>

<table class="history">
  <thead>
    <tr>
      <th>Receipt</th>
      <th>Date</th>
      <th>Service</th>
      <th>Schedule</th>
      <th>Address</th>
      <th>Payment</th>
      <th>Total</th>
      <th>Status</th> <!-- NEW -->
    </tr>
  </thead>
  <tbody>
  <?php foreach ($bookings as $b): 
        $status = strtolower($b['status'] ?? 'Pending');
        $cls = 'status-badge ';
        if ($status==='confirmed') $cls .= 'status-confirmed';
        elseif ($status==='completed') $cls .= 'status-completed';
        elseif ($status==='cancelled') $cls .= 'status-cancelled';
        else $cls .= 'status-pending';
  ?>
    <tr>
      <td><?= htmlspecialchars($b['receipt_no']) ?></td>
      <td><?= htmlspecialchars($b['date']) ?></td>
      <td>
        <?= htmlspecialchars($b['service_name']) ?>
        <?php if (!empty($b['service_desc'])): ?>
          <div style="color:#6b7280; font-size:.85rem;"><?= htmlspecialchars($b['service_desc']) ?></div>
        <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($b['schedule']) ?></td>
      <td><?= htmlspecialchars($b['address_text']) ?></td>
      <td><?= htmlspecialchars($b['payment_method']) ?></td>
      <td><?= money_my($b['total_price']) ?></td>
      <td><span class="<?= $cls ?>"><?= htmlspecialchars(ucfirst($status)) ?></span></td> <!-- NEW -->
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
