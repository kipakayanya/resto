<?php
session_start();
require_once 'config/database.php';

// Ambil data menu
 $sql = "SELECT m.*, k.nama_kategori FROM menu m JOIN kategori_menu k ON m.id_kategori = k.id_kategori ORDER BY k.nama_kategori, m.nama_menu";
 $menu_result = $conn->query($sql);
if (!$menu_result) {
    die("Query Error: " . $conn->error);
}
 $menus = [];
if ($menu_result->num_rows > 0) {
    while($row = $menu_result->fetch_assoc()) {
        $menus[$row['nama_kategori']][] = $row;
    }
}
// Ambil data meja yang tersedia, diurutkan berdasarkan angka
 $meja_result = $conn->query("SELECT * FROM meja WHERE status = 'kosong' ORDER BY CAST(SUBSTRING_INDEX(nomor_meja, ' ', -1) AS UNSIGNED)");
?>
<!DOCTYPE html>
<html lang="id">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Menu Digital - Nama Restoran</title>
 <link rel="stylesheet" href="assets/css/style.css">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
 <header>
  <div class="container">
   <a href="index.php" class="logo">RestoKu</a>
   <nav>
    <a href="#makanan">Makanan</a>
    <a href="#minuman">Minuman</a>
    <a href="#dessert">Dessert</a>
   </nav>
   <a href="login.php" class="btn-staff">Staff</a>
  </div>
 </header>

 <main class="container">
  <!-- Hero Section -->
  <section class="hero">
   <div class="hero-content">
    <h1>Selamat Datang di RestoKu</h1>
    <p>Nikmati berbagai pilihan menu terbaik kami dengan cita rasa yang tak terlupakan.</p>
   </div>
  </section>

  <!-- Table Selection -->
  <section class="table-selection">
   <h2>Silakan Pilih Meja Anda</h2>
   <select id="tableSelect">
    <option value="">-- Pilih Meja --</option>
    <?php while($meja = $meja_result->fetch_assoc()): ?>
    <option value="<?= $meja['id_meja'] ?>"><?= htmlspecialchars($meja['nomor_meja']) ?></option>
    <?php endwhile; ?>
   </select>
  </section>

  <!-- Menu Display -->
  <section class="menu-display">
   <?php foreach ($menus as $category => $items): ?>
   <div class="menu-category" id="<?= strtolower($category) ?>">
    <h2><?= htmlspecialchars($category) ?></h2>
    <div class="menu-grid">
     <?php foreach ($items as $item): ?>
     <div class="menu-item" data-id="<?= $item['id_menu'] ?>" data-name="<?= htmlspecialchars($item['nama_menu']) ?>"
      data-price="<?= $item['harga'] ?>">
      <img src="assets/images/<?= htmlspecialchars($item['gambar']) ?>"
       alt="<?= htmlspecialchars($item['nama_menu']) ?>">
      <div class="menu-info">
       <h3><?= htmlspecialchars($item['nama_menu']) ?></h3>
       <p><?= htmlspecialchars($item['deskripsi']) ?></p>
       <p class="price">Rp. <?= number_format($item['harga'], 0, ',', '.') ?></p>
       <button class="add-to-cart-btn">+ Tambah</button>
      </div>
     </div>
     <?php endforeach; ?>
    </div>
   </div>
   <?php endforeach; ?>
  </section>
 </main>

 <!-- Cart Sidebar -->
 <div id="cart-sidebar" class="cart-sidebar">
  <div class="cart-header">
   <h2>Keranjang Anda</h2>
   <button id="close-cart-btn">&times;</button>
  </div>
  <div id="cart-items" class="cart-items">
   <p>Keranjang kosong.</p>
  </div>
  <div class="cart-footer">
   <h3>Total: <span id="cart-total">Rp. 0</span></h3>
   <input type="text" id="customer-name" placeholder="Nama Pelanggan (Opsional)">
   <button id="checkout-btn">Pesan Sekarang</button>
  </div>
 </div>

 <!-- Cart Toggle Button -->
 <button id="cart-toggle-btn" class="cart-toggle-btn">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
   stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
   <circle cx="9" cy="21" r="1"></circle>
   <circle cx="20" cy="21" r="1"></circle>
   <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
  </svg>
  <span id="cart-count">0</span>
 </button>

 <script src="assets/js/script.js"></script>
</body>

</html>