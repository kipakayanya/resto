<?php
session_start();
// Cek apakah user adalah staff yang login
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('location: login.php');
    exit;
}

require_once 'config/database.php';

// Ambil ID pesanan dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID Pesanan tidak valid.";
    exit;
}
 $id_pesanan = $_GET['id'];

// Ambil data pesanan
 $stmt = $conn->prepare("
    SELECT p.*, m.nomor_meja 
    FROM pesanan p 
    JOIN meja m ON p.id_meja = m.id_meja 
    WHERE p.id_pesanan = ?
");
 $stmt->bind_param("i", $id_pesanan);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Pesanan tidak ditemukan.";
    exit;
}
 $pesanan = $result->fetch_assoc();

// Jika nomor bill belum ada, buat baru
if (empty($pesanan['nomor_bill'])) {
    // Format: BILL-YYYYMMDD-XXXX (4 digit acak)
    $nomor_bill = 'BILL-' . date('Ymd') . '-' . sprintf('%04d', mt_rand(0, 9999));
    
    $update_stmt = $conn->prepare("UPDATE pesanan SET nomor_bill = ? WHERE id_pesanan = ?");
    $update_stmt->bind_param("si", $nomor_bill, $id_pesanan);
    $update_stmt->execute();
    $pesanan['nomor_bill'] = $nomor_bill; // Update variabel lokal
}

// Ambil detail item pesanan
 $items_result = $conn->query("
    SELECT dp.*, m.nama_menu 
    FROM detail_pesanan dp 
    JOIN menu m ON dp.id_menu = m.id_menu 
    WHERE dp.id_pesanan = $id_pesanan
");

?>
<!DOCTYPE html>
<html lang="id">

<head>
 <meta charset="UTF-8">
 <title>Bill - <?= htmlspecialchars($pesanan['nomor_bill']) ?></title>
 <style>
 body {
  font-family: 'Courier New', Courier, monospace;
  margin: 0;
  padding: 20px;
  background: #fff;
 }

 .bill-container {
  max-width: 400px;
  margin: 0 auto;
  border: 1px solid #ccc;
  padding: 20px;
 }

 .bill-header {
  text-align: center;
  border-bottom: 2px dashed #ccc;
  padding-bottom: 10px;
  margin-bottom: 20px;
 }

 .bill-header h1 {
  margin: 0;
  font-size: 24px;
 }

 .bill-header p {
  margin: 5px 0;
 }

 .bill-info,
 .bill-items,
 .bill-footer {
  margin-bottom: 20px;
 }

 .bill-info p,
 .bill-items table {
  margin: 5px 0;
 }

 .bill-items table {
  width: 100%;
  border-collapse: collapse;
 }

 .bill-items th,
 .bill-items td {
  text-align: left;
  padding: 5px 0;
 }

 .bill-items th {
  border-bottom: 1px solid #ccc;
 }

 .bill-items td:last-child,
 .bill-items th:last-child {
  text-align: right;
 }

 .bill-footer {
  border-top: 2px dashed #ccc;
  padding-top: 10px;
 }

 .bill-footer p {
  margin: 5px 0;
 }

 .total {
  font-weight: bold;
  font-size: 18px;
 }

 .no-print {
  text-align: center;
  margin-top: 20px;
 }

 .no-print button {
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
 }

 @media print {
  body {
   padding: 0;
  }

  .no-print {
   display: none;
  }

  .bill-container {
   border: none;
   box-shadow: none;
  }
 }
 </style>
</head>

<body>

 <div class="bill-container">
  <div class="bill-header">
   <h1>RestoKu</h1>
   <p>Jl. Contoh No. 123, Jakarta</p>
   <p>Telp: 021-12345678</p>
  </div>

  <div class="bill-info">
   <p><strong>No. Bill:</strong> <?= htmlspecialchars($pesanan['nomor_bill']) ?></p>
   <p><strong>Tanggal:</strong> <?= date('d M Y H:i') ?></p>
   <p><strong>Meja:</strong> <?= htmlspecialchars($pesanan['nomor_meja']) ?></p>
   <p><strong>Pelanggan:</strong> <?= htmlspecialchars($pesanan['nama_pelanggan'] ?? '-') ?></p>
  </div>

  <div class="bill-items">
   <table>
    <thead>
     <tr>
      <th>Item</th>
      <th>Qty</th>
      <th>Subtotal</th>
     </tr>
    </thead>
    <tbody>
     <?php while ($item = $items_result->fetch_assoc()): ?>
     <tr>
      <td><?= htmlspecialchars($item['nama_menu']) ?></td>
      <td><?= $item['jumlah'] ?></td>
      <td>Rp. <?= number_format($item['harga_satuan'] * $item['jumlah'], 0, ',', '.') ?></td>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>
  </div>

  <div class="bill-footer">
   <p class="total">Total: Rp. <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></p>
   <p>Terima Kasih</p>
  </div>
 </div>

 <div class="no-print">
  <button onclick="window.print()">Cetak Bill</button>
 </div>

</body>

</html>