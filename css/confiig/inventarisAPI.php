<?php
header("Content-Type: application/json");
require_once "conn.php";

// Ambil data JSON
$method = $_SERVER["REQUEST_METHOD"];
$data = json_decode(file_get_contents("php://input"), true);

if ($method === "POST") {
    // CREATE
$id = $data['id'];
$nama = $data['nama'];
$stok = $data['stok'];
$harga = $data['harga'];
$supplier_id = $data['supplier']; // diasumsikan supplier_id
$tanggal_masuk = $data['tanggal_masuk']; // format YYYY-MM-DD

$stmt = $conn->prepare("INSERT INTO barang (id, supplier_id, namaBarang, stok, harga, created_at) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisiis", $id, $supplier_id, $nama, $stok, $harga, $tanggal_masuk);


if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil ditambahkan"]);
} else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
}

} elseif ($method === "PUT") {
    // UPDATE
$id = $data['id'];
$nama = $data['nama'];
$stok = $data['stok'];
$harga = $data['harga'];
$supplier_id = $data['supplier'];
$tanggal_masuk = $data['tanggal_masuk'];

$stmt = $conn->prepare("UPDATE barang SET supplier_id=?, namaBarang=?, stok=?, harga=?, created_at=? WHERE id=?");
$stmt->bind_param("ississ", $supplier_id, $nama, $stok, $harga, $tanggal_masuk, $id);

if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil diperbarui"]);
} else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
}

} elseif ($method === "DELETE") {
    // DELETE
$id = $data['id'];

$stmt = $conn->prepare("DELETE FROM barang WHERE id=?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Barang berhasil dihapus"]);
} else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
}

} elseif ($method === "GET") {
    // READ
$result = $conn->query("SELECT barang.*, supplier.nama_supplier FROM barang JOIN supplier ON barang.supplier_id = supplier.id");
$rows = [];

while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
}

echo json_encode($rows);
}
?>
