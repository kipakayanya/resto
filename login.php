<?php
session_start();
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    if ($_SESSION['user_role'] == 'admin') {
        header('location: admin_dashboard.php');
    } else {
        header('location: kitchen_dashboard.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Login Staff</title>
 <link rel="stylesheet" href="assets/css/style.css">
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="login-page">
 <div class="login-container">
  <form action="auth.php" method="post">
   <h2>Login Staff</h2>
   <?php if (isset($_GET['error'])) echo '<p class="error">Username atau password salah!</p>'; ?>
   <div class="form-group">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" required>
   </div>
   <div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>
   </div>
   <button type="submit" class="btn">Login</button>
   <p><small>Admin: admin / admin123 | Kitchen: kitchen / kitchen123</small></p>
  </form>
 </div>
</body>

</html>