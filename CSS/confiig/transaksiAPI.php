<?php
header('Content-Type: application/json');
include 'conn.php';
session_start();

try {
    if (
        !isset($_POST['user_id'], $_POST['total_harga'], $_POST['uang_bayar'], $_POST['kembalian'], $_POST['items'])
    ) {
        throw new Exception("Data tidak lengkap");
    }

    $user_id = $_POST['user_id'];
    $total_harga = $_POST['total_harga'];
    $uang_bayar = $_POST['uang_bayar'];
    $kembalian = $_POST['kembalian'];
    $items = json_decode($_POST['items'], true); // items dikirim sebagai string JSON

    if (!is_array($items)) {
        throw new Exception("Format items harus array");
    }

    mysqli_begin_transaction($conn);

    $stmt = $conn->prepare("INSERT INTO transaksi (user_id, total_harga, uang_bayar, kembalian, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiii", $user_id, $total_harga, $uang_bayar, $kembalian);
    $stmt->execute();
    $transaksi_id = $stmt->insert_id;

    $stmt_detail = $conn->prepare("INSERT INTO transaksi_detail (transaksi_id, barang_id, jumlah, harga, total) VALUES (?, ?, ?, ?, ?)");
    $stmt_update_stok = $conn->prepare("UPDATE barang SET stok = stok - ? WHERE id = ?");

    foreach ($items as $item) {
        if (!isset($item['barangId'], $item['jumlah'], $item['harga'], $item['total'])) {
            throw new Exception("Item transaksi tidak lengkap");
        }

        $barang_id = $item['barangId'];
        $jumlah = $item['jumlah'];
        $harga = $item['harga'];
        $total = $item['total'];

        $stmt_detail->bind_param("iiiii", $transaksi_id, $barang_id, $jumlah, $harga, $total);
        $stmt_detail->execute();

        $stmt_update_stok->bind_param("ii", $jumlah, $barang_id);
        $stmt_update_stok->execute();
    }

    mysqli_commit($conn);
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
