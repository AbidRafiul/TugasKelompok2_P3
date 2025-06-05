<?php
require_once "confiig/conn.php";

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Tambah data
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["aksi"])) {
    $aksi = $_POST["aksi"];
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $stok = $_POST["stok"];
    $harga = $_POST["harga"];
    $supplier = $_POST["supplier"];
    $tanggal = $_POST["tanggal_masuk"];

    if ($aksi === "tambah") {
        $stmt = $conn->prepare("INSERT INTO barang (id, namaBarang, stok, harga, supplier_id, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiss", $id, $nama, $stok, $harga, $supplier, $tanggal);
        $stmt->execute();
    } elseif ($aksi === "edit") {
        $stmt = $conn->prepare("UPDATE barang SET namaBarang=?, stok=?, harga=?, supplier_id=?, created_at=? WHERE id=?");
        $stmt->bind_param("siisss", $nama, $stok, $harga, $supplier, $tanggal, $id);
        $stmt->execute();
    } elseif ($aksi === "hapus") {
        $stmt = $conn->prepare("DELETE FROM barang WHERE id=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
    }

    header("Location: inventaris.php");
    exit();
}

$query = "SELECT barang.*, supplier.nama_supplier FROM barang JOIN supplier ON barang.supplier_id = supplier.id ORDER BY barang.created_at DESC";
$result = $conn->query($query);
$supplier_result = $conn->query("SELECT id, nama_supplier FROM supplier");
$suppliers = [];
while ($row = $supplier_result->fetch_assoc()) {
    $suppliers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inventaris - Toko Risky</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
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
    .navbar { box-shadow: 0 2px 6px rgba(0, 0, 0, 0.6); }
    h2 { text-align: left; margin-bottom: 20px; text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.5); }
    .overlay { background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)); min-height: 100vh; padding-top: 70px; }
    .table { background-color: rgba(255, 255, 255, 0.05); color: white; backdrop-filter: blur(4px); }
    .table th, .table td { vertical-align: middle; }
    .modal-content { background-color: #1e1e1e; color: white; border-radius: 10px; }
    .btn-primary { background-color: white; color: #1e1e1e; font-weight: bold; border: none; }
    .btn-primary:hover { background-color: #bcbcbc; color: #1e1e1e; }
    .btn-warning, .btn-danger { border: none; }
    .form-control, .form-select { background-color: #2c2c2c; color: white; border: 1px solid #444; }
    .form-control:focus, .form-select:focus { background-color: #2c2c2c; color: white; border-color: #66bb6a; box-shadow: none; }
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
        <li class="nav-item"><a class="nav-link active" href="inventaris.php">Inventaris</a></li>
        <li class="nav-item"><a class="nav-link" href="transaksi.php">Transaksi</a></li>
        <li class="nav-item"><a class="nav-link" href="laporan.php">Laporan</a></li>
        <li class="nav-item"><a class="nav-link" href="supplier.php">Data Supplier</a></li>
        <li class="nav-item"><a class="nav-link" href="profil.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="overlay">
  <div class="container mt-5 pt-4">
    <h2>Inventaris Barang</h2>
    <button class="btn btn-primary mb-3" onclick="bukaFormTambah()">Tambah Barang</button>

    <div class="table-responsive">
      <table class="table table-bordered text-white">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Supplier</th>
            <th>Tanggal Masuk</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['namaBarang'] ?></td>
            <td><?= $row['stok'] ?> Kg</td>
            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
            <td><?= $row['nama_supplier'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
              <button class="btn btn-warning btn-sm" onclick='editBarang(<?= json_encode($row) ?>)'><i class="bi bi-pencil-square"></i></button>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="hidden" name="aksi" value="hapus">
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus barang ini?')"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center">Data inventaris tidak ditemukan</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalForm" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST">
        <input type="hidden" name="aksi" id="aksiForm" value="tambah">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFormLabel">Tambah Barang</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">ID</label>
              <input type="text" class="form-control" name="id" id="idBarang" required>
            </div>
            <div class="col-md-8">
              <label class="form-label">Nama</label>
              <input type="text" class="form-control" name="nama" id="namaBarang" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Stok</label>
              <input type="number" class="form-control" name="stok" id="stokBarang" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Harga</label>
              <input type="number" class="form-control" name="harga" id="hargaBarang" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Supplier</label>
              <select class="form-select" name="supplier" id="supplierBarang" required>
                <option value="">Pilih Supplier</option>
                <?php foreach ($suppliers as $s): ?>
                  <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama_supplier']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" class="form-control" name="tanggal_masuk" id="tanggalMasuk" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function bukaFormTambah() {
  document.getElementById('modalFormLabel').innerText = 'Tambah Barang';
  document.getElementById('aksiForm').value = 'tambah';
  document.getElementById('idBarang').readOnly = false;

  document.getElementById('idBarang').value = '';
  document.getElementById('namaBarang').value = '';
  document.getElementById('stokBarang').value = '';
  document.getElementById('hargaBarang').value = '';
  document.getElementById('supplierBarang').value = '';
  document.getElementById('tanggalMasuk').value = '';

  new bootstrap.Modal(document.getElementById('modalForm')).show();
}

function editBarang(data) {
  document.getElementById('modalFormLabel').innerText = 'Edit Barang';
  document.getElementById('aksiForm').value = 'edit';
  document.getElementById('idBarang').readOnly = true;

  document.getElementById('idBarang').value = data.id;
  document.getElementById('namaBarang').value = data.namaBarang;
  document.getElementById('stokBarang').value = data.stok;
  document.getElementById('hargaBarang').value = data.harga;
  document.getElementById('supplierBarang').value = data.supplier_id;
  document.getElementById('tanggalMasuk').value = data.created_at;

  new bootstrap.Modal(document.getElementById('modalForm')).show();
}
</script>

</body>
</html>
