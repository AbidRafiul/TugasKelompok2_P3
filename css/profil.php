<?php
include 'confiig/conn.php';
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Toko - Toko Risky</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
      background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75));
      min-height: 100vh;
      padding-top: 70px;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.05);
      border: none;
      color: white;
      backdrop-filter: blur(6px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      border-radius: 15px;
    }

    .card-header {
      background-color: rgba(255, 255, 255, 0.1);
      border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modal-content {
      background-color: rgba(0, 0, 0, 0.85);
      color: white;
    }
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
        <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="supplier.php">Data Supplier</a></li>
        <li class="nav-item"><a class="nav-link active" href="profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="overlay">
  <div class="container mt-5 pt-4">
    <div class="row">
      <!-- Profil Toko -->
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-info-circle-fill"></i> Tentang Toko
          </div>
          <div class="card-body">
            <h5 class="card-title">Toko Risky Bawang dan Palawija</h5>
            <p><strong>Filosofi:</strong> Menyediakan rempah terbaik dengan pelayanan terpercaya.</p>
            <p><strong>Lokasi:</strong> Madiun, Jawa Timur</p>
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.031671631728!2d111.5214539!3d-7.6328885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79bff75abfb585%3A0x6aeb352d7d67e7cb!2sRisky%20Bawang!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
              width="100%" height="250" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </div>

      <!-- Data Pegawai -->
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header">
            <i class="bi bi-person-fill"></i> Data Pegawai
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <h5 class="mb-0">Daftar Pegawai</h5>
              <button class="btn btn-sm btn-primary" onclick="openAddModal()">
                <i class="bi bi-plus-circle"></i> Tambah Pegawai
              </button>
            </div>
            <ul class="list-group list-group-flush" id="pegawai-list">
              <?php
                $result = $conn->query("SELECT * FROM users");
                while ($row = $result->fetch_assoc()) {
                  echo '<li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: rgba(255,255,255,0.05); color: white;">';
                  echo '<div>';
                  echo '<strong>Nama:</strong> ' . htmlspecialchars($row['nama_lengkap']) . '<br>';
                  echo '<strong>Role:</strong> ' . htmlspecialchars($row['role']) . '<br>';
                  echo '<strong>Email:</strong> ' . htmlspecialchars($row['email']);
                  echo '</div>';
                  echo '<button class="btn btn-sm btn-warning" onclick=\'openEditModal(' . json_encode($row) . ')\'><i class="bi bi-pencil-square"></i> Edit</button>';
                  echo '</li>';
                }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit Pegawai -->
<div class="modal fade" id="pegawaiModal" tabindex="-1" aria-labelledby="pegawaiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="pegawaiForm">
        <div class="modal-header">
          <h5 class="modal-title" id="pegawaiModalLabel">Tambah/Edit Pegawai</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="pegawaiId">
          <div class="mb-3">
            <label for="pegawaiNama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="pegawaiNama" required>
          </div>
          <div class="mb-3">
            <label for="pegawaiRole" class="form-label">Role</label>
            <input type="text" class="form-control" id="pegawaiRole" required>
          </div>
          <div class="mb-3">
            <label for="pegawaiEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="pegawaiEmail" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openAddModal() {
  document.getElementById('pegawaiForm').reset();
  document.getElementById('pegawaiId').value = '';
  document.getElementById('pegawaiModalLabel').innerText = 'Tambah Pegawai';
  new bootstrap.Modal(document.getElementById('pegawaiModal')).show();
}

function openEditModal(data) {
  document.getElementById('pegawaiId').value = data.id_user;
  document.getElementById('pegawaiNama').value = data.nama_lengkap;
  document.getElementById('pegawaiRole').value = data.role;
  document.getElementById('pegawaiEmail').value = data.email;
  document.getElementById('pegawaiModalLabel').innerText = 'Edit Pegawai';
  new bootstrap.Modal(document.getElementById('pegawaiModal')).show();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
