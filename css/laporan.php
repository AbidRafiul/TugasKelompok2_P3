<?php
include 'confiig/conn.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$query = "
  SELECT 
    t.created_at AS tanggal,
    b.namaBarang AS nama_barang,
    td.jumlah,
    td.harga,
    td.total
  FROM transaksi_detail td
  JOIN transaksi t ON td.transaksi_id = t.id
  JOIN barang b ON td.barang_id = b.id
  ORDER BY t.created_at DESC
";

$result = mysqli_query($conn, $query);

$laporan = [];
$totalPendapatan = 0;

while ($row = mysqli_fetch_assoc($result)) {
  $laporan[] = $row;
  $totalPendapatan += $row['total'];
}
?>

<!-- HTML Lanjutan (sama seperti sebelumnya) -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Laporan Penjualan - Toko Risky</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body {
      background-image: url('gambar/rempah.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: white;
      margin: 0;
      padding: 0;
    }
    .navbar { box-shadow: 0 2px 6px rgba(0, 0, 0, 0.6); }
    h2 { text-align: left; margin-bottom: 20px; text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.5); }
    .overlay { background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)); min-height: 100vh; padding-top: 70px; }
    .table { background-color: rgba(255, 255, 255, 0.05); color: white; backdrop-filter: blur(4px); }
    .table th, .table td { vertical-align: middle; }
    .btn-primary { background-color: white; color: #1e1e1e; font-weight: bold; border: none; }
    .btn-primary:hover { background-color: #bcbcbc; color: #1e1e1e; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-shop"></i> Toko Risky</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="inventaris.php">Inventaris</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link active" href="laporan.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="supplier.php">Data Supplier</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="overlay">
  <div class="container mt-5 pt-4">
    <h2>Laporan Penjualan</h2>
    <div class="table-responsive">
      <table class="table table-bordered mt-3">
        <thead class="table-dark">
          <tr>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($laporan as $item): ?>
            <tr>
              <td><?= $item['tanggal'] ?></td>
              <td><?= $item['nama_barang'] ?></td>
              <td><?= $item['jumlah'] ?></td>
              <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
              <td>Rp <?= number_format($item['total'], 0, ',', '.') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <h5 class="text-end mt-3">Total Pendapatan: <strong>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></strong></h5>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
