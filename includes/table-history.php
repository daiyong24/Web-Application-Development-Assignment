<?php if (!isset($bookings)) { $bookings = []; } ?>

<div class="controls">
  <div class="page-size">
    <label>Show</label>
    <select>
      <option>10</option>
      <option selected>25</option>
      <option>50</option>
      <option>100</option>
    </select>
    <span>entries</span>
  </div>

  <div class="search">
    <label>Search:</label>
    <input type="text" placeholder="Type to filter…">
  </div>
</div>

<div class="tablewrap">
  <table>
    <thead>
      <tr>
        <th style="width:64px;">No</th>
        <th>Receipt/Cancellation No</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Booking Date</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$bookings): ?>
        <tr><td colspan="5" class="empty">No data available in table</td></tr>
      <?php else: ?>
        <?php $i=1; foreach ($bookings as $b): ?>
          <tr>
            <td><?= $i++; ?></td>
            <td><?= htmlspecialchars('BK' . str_pad($b['receipt_no'], 6, '0', STR_PAD_LEFT)) ?></td>
            <td>
              <div><strong><?= htmlspecialchars($b['service_name']) ?></strong> — <?= htmlspecialchars($b['service_desc']) ?></div>
              <div style="color:#6b7280; font-size:12px;">
                Schedule: <?= htmlspecialchars($b['schedule']) ?> |
                Address: <?= htmlspecialchars($b['address_text']) ?> |
                Method: <?= htmlspecialchars($b['payment_method']) ?>
              </div>
            </td>
            <td><?= money_my($b['total_price']) ?></td>
            <td><?= htmlspecialchars($b['date']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="pager">
  <button class="prev">Previous</button>
  <button class="next">Next</button>
</div>
