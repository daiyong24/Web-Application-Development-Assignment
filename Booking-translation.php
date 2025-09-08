<?php
// transaction_history.php  — Booking/Transaction history (oldest → newest)

if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php'); exit;
}

$userId = (int)$_SESSION['user_id'];

$sort = strtolower($_GET['sort'] ?? 'asc');
$sort = $sort === 'desc' ? 'DESC' : 'ASC';   // whitelist

$sql = "
SELECT 
  b.id,
  CONCAT('BK', LPAD(b.id, 6, '0')) AS receipt_no,
  s.name        AS service_name,
  s.description AS service_desc,
  b.schedule,
  b.date,
  b.payment_method,
  b.total_price,
  a.address_text
FROM bookings b
JOIN services  s ON s.id = b.service_id
JOIN addresses a ON a.id = b.address_id
WHERE b.user_id = ?
ORDER BY b.id ASC   -- or DESC if you want newest first
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

function money_my($v){ return 'RM ' . number_format((float)$v, 2); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Booking History — Shine & Sparkle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/style-history.css">
  <script src="js/script.js" defer></script>
  <script src="js/style-switcher.js" defer></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body class="history-page">

<?php include 'components/header.php'; ?>

<div class="container">
  <div class="crumbs"><a href="index.php">Home</a> / <span>Booking History</span></div>
  <h1 class="title">Booking History</h1>
  
  <div class="card">
    <?php include __DIR__ . '/includes/table-history.php'; ?>
  </div>
</div>

<script>
  // Simple client-side search/pagination for the included table
  document.querySelectorAll('.card').forEach(function(scope){
    const table  = scope.querySelector('table');
    if(!table) return;
    const rows   = Array.from(table.querySelectorAll('tbody tr'));
    const q      = scope.querySelector('.search input');
    const sizeEl = scope.querySelector('.page-size select');
    const prev   = scope.querySelector('.pager .prev');
    const next   = scope.querySelector('.pager .next');

    let filtered = rows, page = 0;
    function render(){
      const size = parseInt(sizeEl.value,10);
      rows.forEach(r => r.style.display = 'none');
      filtered.slice(page*size, (page+1)*size).forEach(r => r.style.display = 'table-row');
      if (prev) prev.disabled = (page===0);
      if (next) next.disabled = ((page+1)*size >= filtered.length);
    }
    function applyFilter(){
      const s = (q && q.value || '').toLowerCase();
      filtered = !s ? rows : rows.filter(r => r.innerText.toLowerCase().includes(s));
      page = 0; render();
    }
    q && q.addEventListener('input', applyFilter);
    sizeEl && sizeEl.addEventListener('change', ()=>{ page=0; render(); });
    prev && prev.addEventListener('click', ()=>{ if(page>0){ page--; render(); }});
    next && next.addEventListener('click', ()=>{ const size=parseInt(sizeEl.value,10); if((page+1)*size < filtered.length){ page++; render(); }});
    applyFilter();
  });
</script>

<?php include 'components/footer.php'; ?>
</body>
</html>
