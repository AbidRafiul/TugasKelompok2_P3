<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "db_tokorisky");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Koneksi gagal']);
    exit;
}

$result = $conn->query("SELECT id, nama_supplier FROM supplier");
$suppliers = [];

while ($row = $result->fetch_assoc()) {
    $suppliers[] = $row;
}

echo json_encode($suppliers);
?>
