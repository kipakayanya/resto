<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || ($_SESSION['user_role'] !== 'kitchen' && $_SESSION['user_role'] !== 'admin')) {
    header('location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Admin Dashboard</title>
 <link rel="stylesheet" href="assets/css/style.css">
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="dashboard-page">
 <header>
  <h1>Admin Dashboard</h1>
  <a href="logout.php" class="logout-btn">Logout</a>
 </header>
 <main class="container admin-welcome">
  <h2>Selamat datang, Admin!</h2>
  <p>Pilih aksi yang ingin Anda lakukan:</p>
  <div class="admin-links">
   <a href="kitchen_dashboard.php">Lihat Kitchen Display System</a>
   <!-- Tautan lain bisa ditambahkan di sini -->
  </div>
 </main>
</body>

</html>