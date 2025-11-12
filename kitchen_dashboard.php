<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || ($_SESSION['user_role'] !== 'kitchen' && $_SESSION['user_role'] !== 'admin')) {
    header('location: login.php');
    exit;
}
require_once 'config/database.php';

 $orders_result = $conn->query("SELECT p.*, m.nomor_meja FROM pesanan p JOIN meja m ON p.id_meja = m.id_meja WHERE p.status_pesanan != 'dibayar' ORDER BY p.waktu_pesan ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Kitchen Display System</title>
 <link rel="stylesheet" href="assets/css/style.css">
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="dashboard-page">
 <header>
  <h1>Kitchen Display System</h1>
  <a href="logout.php" class="logout-btn">Logout</a>
 </header>
 <main class="dashboard-container">
  <?php if ($orders_result->num_rows > 0): ?>
  <?php while($order = $orders_result->fetch_assoc()): ?>
  <div class="order-card status-<?= $order['status_pesanan'] ?>" data-order-id="<?= $order['id_pesanan'] ?>">
   <div class="order-header">
    <h2>Order #<?= $order['id_pesanan'] ?></h2>
    <span class="order-time"><?= date('H:i', strtotime($order['waktu_pesan'])) ?></span>
   </div>
   <div class="order-details">
    <p><strong>Meja:</strong> <?= htmlspecialchars($order['nomor_meja']) ?></p>
    <p><strong>Pelanggan:</strong> <?= htmlspecialchars($order['nama_pelanggan'] ?? '-') ?></p>
   </div>
   <div class="order-items">
    <?php
                        $id_pesanan = $order['id_pesanan'];
                        $items_result = $conn->query("SELECT dp.*, m.nama_menu FROM detail_pesanan dp JOIN menu m ON dp.id_menu = m.id_menu WHERE dp.id_pesanan = $id_pesanan");
                        while($item = $items_result->fetch_assoc()):
                        ?>
    <div class="order-item-row">
     <span class="item-qty"><?= $item['jumlah'] ?>x</span>
     <span class="item-name"><?= htmlspecialchars($item['nama_menu']) ?></span>
    </div>
    <?php endwhile; ?>
   </div>
   <div class="order-actions">
    <?php if ($order['status_pesanan'] == 'baru'): ?>
    <button class="btn-action" data-status="diproses">Proses</button>
    <?php endif; ?>
    <?php if ($order['status_pesanan'] == 'diproses'): ?>
    <button class="btn-action" data-status="selesai">Selesai</button>
    <?php endif; ?>
    <?php if ($order['status_pesanan'] == 'selesai'): ?>
    <button class="btn-action" data-status="dibayar">Bayar</button>
    <?php endif; ?>
   </div>
  </div>
  <?php endwhile; ?>
  <?php else: ?>
  <div class="no-orders">
   <p>Belum ada pesanan baru.</p>
  </div>
  <?php endif; ?>
 </main>
 <script src="assets/js/dashboard_script.js"></script>
</body>

</html>