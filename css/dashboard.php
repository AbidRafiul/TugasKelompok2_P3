<?php
include 'confiig/conn.php';

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$resultBarang = $conn->query("SELECT COUNT(*) as total FROM barang");
$totalBarang = $resultBarang->fetch_assoc()['total'] ?? 0;

$resultTransaksi = $conn->query("SELECT COUNT(*) as total FROM transaksi WHERE DATE(created_at) = CURDATE()");
$totalTransaksi = $resultTransaksi->fetch_assoc()['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - Toko Risky</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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

    .overlay {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
      min-height: 100vh;
      padding-top: 70px;
    }

    .navbar {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
    }

    h1 {
      text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.6);
      margin-bottom: 30px;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.05);
      border: none;
      color: white;
      backdrop-filter: blur(6px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      border-radius: 15px;
    }

    .card h5 {
      font-weight: bold;
    }

    .card a {
      color: #fff;
      font-weight: bold;
      text-decoration: underline;
    }

    .card a:hover {
      color: #ddd;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-shop"></i> Toko Risky</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="inventaris.php">Inventaris</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="supplier.php">Data Supplier</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="overlay">
  <div class="container mt-5 pt-4">
    <h1>Dashboard</h1>
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card p-3">
          <div class="card-body">
            <h5 class="card-title">Total Barang</h5>
            <p class="card-text" id="total-barang"><?= $totalBarang ?> item</p>
            <a href="inventaris.php">Lihat Inventaris</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card p-3">
          <div class="card-body">
            <h5 class="card-title">Transaksi Hari Ini</h5>
            <p class="card-text" id="total-transaksi"><?= $totalTransaksi?></p>
            <a href="transaksi.php">Lihat Transaksi</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card p-3">
          <div class="card-body">
            <h5 class="card-title">Total Pendapatan Hari Ini</h5>
            <p class="card-text" id="total-pendapatan">Rp.100.000</p>
            <a href="laporan.php">Lihat Laporan</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
