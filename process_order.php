<?php
require_once 'config/database.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success' => false]); exit; }

 $id_meja = $_POST['id_meja'];
 $nama_pelanggan = !empty($_POST['nama_pelanggan']) ? $_POST['nama_pelanggan'] : null;
 $cart_items = json_decode($_POST['cart_items'], true);
 $total_harga = 0;
foreach ($cart_items as $item) { $total_harga += $item['price'] * $item['quantity']; }

 $conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO pesanan (id_meja, nama_pelanggan, total_harga, status_pesanan) VALUES (?, ?, ?, 'baru')");
    $stmt->bind_param("isd", $id_meja, $nama_pelanggan, $total_harga);
    $stmt->execute();
    $id_pesanan = $conn->insert_id;

    $stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, harga_satuan) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $stmt_detail->bind_param("iiid", $id_pesanan, $item['id'], $item['quantity'], $item['price']);
        $stmt_detail->execute();
    }

    $stmt_meja = $conn->prepare("UPDATE meja SET status = 'terisi' WHERE id_meja = ?");
    $stmt_meja->bind_param("i", $id_meja);
    $stmt_meja->execute();

    $conn->commit();
    echo json_encode(['success' => true, 'order_id' => $id_pesanan]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
 $stmt->close(); $stmt_detail->close(); $stmt_meja->close(); $conn->close();
?>