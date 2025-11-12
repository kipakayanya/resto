<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) { echo json_encode(['success' => false]); exit; }

require_once 'config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $status_pesanan = $_POST['status_pesanan'];
    if ($status_pesanan == 'dibayar') {
        $stmt = $conn->prepare("SELECT id_meja FROM pesanan WHERE id_pesanan = ?");
        $stmt->bind_param("i", $id_pesanan); $stmt->execute(); $pesanan = $stmt->get_result()->fetch_assoc(); $id_meja = $pesanan['id_meja']; $stmt->close();
        $stmt_meja = $conn->prepare("UPDATE meja SET status = 'kosong' WHERE id_meja = ?");
        $stmt_meja->bind_param("i", $id_meja); $stmt_meja->execute(); $stmt_meja->close();
    }
    $stmt = $conn->prepare("UPDATE pesanan SET status_pesanan = ? WHERE id_pesanan = ?");
    $stmt->bind_param("si", $status_pesanan, $id_pesanan);
    if ($stmt->execute()) { echo json_encode(['success' => true]); } else { echo json_encode(['success' => false]); }
    $stmt->close();
}
 $conn->close();
?>