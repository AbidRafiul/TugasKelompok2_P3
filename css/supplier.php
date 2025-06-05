<?php
require 'confiig/conn.php';
session_start();

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Tambah Supplier
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $noHp = $_POST['noHp'];
    $alamat = $_POST['alamat'];
    $supply = $_POST['supply_barang'];

    $query = "INSERT INTO supplier (id, nama_supplier, noHp, alamat, supply_barang, created_at) 
              VALUES ('$id', '$nama', '$noHp', '$alamat', '$supply', NOW())";
    mysqli_query($conn, $query);
    header("Location: supplier.php");
    exit;
}

// Edit Supplier
if (isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $noHp = $_POST['noHp'];
    $alamat = $_POST['alamat'];
    $supply = $_POST['supply_barang'];

    $query = "UPDATE supplier SET nama_supplier='$nama', noHp='$noHp', alamat='$alamat', supply_barang='$supply' WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: supplier.php");
    exit;
}

// Hapus Supplier
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM supplier WHERE id='$id'");
    header("Location: supplier.php");
    exit;
}

// Ambil data supplier
$result = mysqli_query($conn, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Supplier - Toko Risky</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-image: url('gambar/rempah.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
    }
    .overlay { background-color: rgba(0,0,0,0.7); min-height: 100vh; padding-top: 70px; }
    .table { background-color: rgba(255,255,255,0.05); color: white; backdrop-filter: blur(4px); }
    .modal-content { background-color: #1e1e1e; color: white; border-radius: 10px; }
    .form-control, .form-select {
      background-color: #2c2c2c; color: white; border: 1px solid #444;
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
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="inventaris.php">Inventaris</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link active" href="supplier.php">Data Supplier</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="overlay">
  <div class="container mt-5 pt-4">
    <h2>Data Supplier</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalSupplier" onclick="resetForm()">Tambah Supplier</button>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Supply Barang</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td><?= $row['id']; ?></td>
              <td><?= $row['nama_supplier']; ?></td>
              <td><?= $row['noHp']; ?></td>
              <td><?= $row['alamat']; ?></td>
              <td><?= $row['supply_barang']; ?></td>
              <td><?= $row['created_at']; ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick='editSupplier(<?= json_encode($row) ?>)'><i class="bi bi-pencil-square"></i></button>
                <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus supplier ini?')"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalSupplier" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="aksi" id="aksiForm" value="tambah">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFormLabel">Tambah Supplier</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>ID Supplier</label>
            <input type="text" class="form-control" name="id" id="id" required>
          </div>
          <div class="mb-3">
            <label>Nama Supplier</label>
            <input type="text" class="form-control" name="nama" id="nama" required>
          </div>
          <div class="mb-3">
            <label>No HP</label>
            <input type="text" class="form-control" name="noHp" id="noHp" required>
          </div>
          <div class="mb-3">
            <label>Alamat</label>
            <textarea class="form-control" name="alamat" id="alamat" required></textarea>
          </div>
          <div class="mb-3">
            <label>Supply Barang</label>
            <input type="text" class="form-control" name="supply_barang" id="supply_barang" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Simpan</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function editSupplier(data) {
    document.getElementById('aksiForm').value = 'edit';
    document.getElementById('modalFormLabel').innerText = 'Edit Supplier';
    document.getElementById('id').value = data.id;
    document.getElementById('id').readOnly = true;
    document.getElementById('nama').value = data.nama_supplier;
    document.getElementById('noHp').value = data.noHp;
    document.getElementById('alamat').value = data.alamat;
    document.getElementById('supply_barang').value = data.supply_barang;

    var modal = new bootstrap.Modal(document.getElementById('modalSupplier'));
    modal.show();
  }

  function resetForm() {
    document.getElementById('aksiForm').value = 'tambah';
    document.getElementById('modalFormLabel').innerText = 'Tambah Supplier';
    document.getElementById('id').readOnly = false;
    document.getElementById('id').value = '';
    document.getElementById('nama').value = '';
    document.getElementById('noHp').value = '';
    document.getElementById('alamat').value = '';
    document.getElementById('supply_barang').value = '';
  }
</script>

</body>
</html>
