<?php
session_start();
include 'confiig/conn.php';

// Proteksi jika belum login
if (!isset($_SESSION['id_users'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transaksi - Toko Risky</title>
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
    }

    .overlay {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
      min-height: 100vh;
      padding-top: 70px;
    }

    .table {
      background-color: rgba(255, 255, 255, 0.05);
      color: white;
      backdrop-filter: blur(4px);
    }

    .form-control, .form-select {
      background-color:#2c2c2c;
      color: white;
    }

    .form-control::placeholder {
  color: #ccc;
  opacity: 1; 
}
  </style>
</head>
<body>
  <!-- Kirim session user_id ke JS -->
  <script>
    const userId = <?= $_SESSION['id_users']; ?>;
  </script>

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
          <li class="nav-item"><a class="nav-link active" href="transaksi.php">Transaksi</a></li>
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
      <h2>Transaksi Kasir</h2>

      <form id="formTransaksi" class="row g-3 mb-3">
        <div class="col-md-5">
          <select class="form-select" id="pilihBarang" required>
            <option value="">Pilih Barang</option>
            <?php
            $query = mysqli_query($conn, "SELECT * FROM barang");
            while ($row = mysqli_fetch_assoc($query)) {
              echo "<option value='{$row['id']}' data-harga='{$row['harga']}' data-stok='{$row['stok']}'>{$row['namaBarang']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-md-3">
          <input type="number" id="jumlahBeli" class="form-control" min="1" placeholder="Jumlah (Kg)" required>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">Tambah</button>
        </div>
      </form>

      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga/kg</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tabelTransaksi"></tbody>
      </table>

      <h5 class="text-end">Total Belanja: <span id="totalHarga">Rp 0</span></h5>

      <div class="row mt-4">
        <div class="col-md-4">
          <label for="uangPembeli" class="form-label">Uang Pembeli (Rp)</label>
          <input type="number" class="form-control" id="uangPembeli" min="0" placeholder="Masukkan jumlah uang">
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button class="btn btn-primary" onclick="selesaikanTransaksi()">Selesai Transaksi</button>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <h5 class="w-100">Kembalian: <span id="kembalian">Rp 0</span></h5>
        </div>
      </div>
    </div>
  </div>

  <script>
  let transaksiItems = [];

  document.getElementById('formTransaksi').addEventListener('submit', function(e) {
    e.preventDefault();
    const select = document.getElementById('pilihBarang');
    const selectedOption = select.options[select.selectedIndex];
    const barangId = select.value;
    const barangNama = selectedOption.text;
    const harga = parseInt(selectedOption.getAttribute('data-harga'));
    const stok = parseInt(selectedOption.getAttribute('data-stok'));
    const jumlah = parseInt(document.getElementById('jumlahBeli').value);

    if (jumlah > stok) {
      alert('Jumlah melebihi stok tersedia!');
      return;
    }

    const total = harga * jumlah;

    const existingIndex = transaksiItems.findIndex(item => item.barangId === barangId);
    if (existingIndex !== -1) {
      transaksiItems[existingIndex].jumlah += jumlah;
      transaksiItems[existingIndex].total += total;
    } else {
      transaksiItems.push({ barangId, nama: barangNama, jumlah, harga, total });
    }

    renderTabelTransaksi();
    document.getElementById('jumlahBeli').value = '';
    document.getElementById('pilihBarang').selectedIndex = 0;
  });

  function renderTabelTransaksi() {
    const tbody = document.getElementById('tabelTransaksi');
    tbody.innerHTML = '';
    transaksiItems.forEach((item, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${item.nama}</td>
        <td>${item.jumlah}</td>
        <td>Rp ${item.harga.toLocaleString()}</td>
        <td>Rp ${item.total.toLocaleString()}</td>
        <td><button class="btn btn-danger btn-sm" onclick="hapusRow(${index})">Hapus</button></td>
      `;
      tbody.appendChild(tr);
    });
    updateTotalHarga();
  }

  function hapusRow(index) {
    transaksiItems.splice(index, 1);
    renderTabelTransaksi();
  }

  function updateTotalHarga() {
    const total = transaksiItems.reduce((sum, item) => sum + item.total, 0);
    document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString();
  }

  function selesaikanTransaksi() {
    const totalHarga = transaksiItems.reduce((sum, item) => sum + item.total, 0);
    const uangPembeli = parseInt(document.getElementById('uangPembeli').value);
    const kembalian = uangPembeli - totalHarga;

    if (isNaN(uangPembeli) || uangPembeli < totalHarga) {
      alert('Uang pembeli kurang!');
      document.getElementById('kembalian').textContent = 'Rp 0';
      return;
    }

    document.getElementById('kembalian').textContent = 'Rp ' + kembalian.toLocaleString();

    const formData = new FormData();
    formData.append('user_id', userId); // Ambil dari session
    formData.append('total_harga', totalHarga);
    formData.append('uang_bayar', uangPembeli);
    formData.append('kembalian', kembalian);
    formData.append('items', JSON.stringify(transaksiItems));

    fetch('confiig/transaksiAPI.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        alert('Transaksi berhasil!');
        location.reload();
      } else {
        alert('Gagal: ' + data.message);
      }
    });
  }
</script>
</body>
</html>
