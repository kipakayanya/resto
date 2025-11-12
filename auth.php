<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header('location: admin_dashboard.php');
            } else {
                header('location: kitchen_dashboard.php');
            }
            exit;
        }
    }
    header('location: login.php?error=1');
    exit;
}
?>