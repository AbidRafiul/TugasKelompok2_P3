<?php
include 'confiig/conn.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $alamat = $_POST['alamat'];
    $role = $_POST['role'];

    // Validasi role
    $allowedRoles = ['admin', 'kasir'];
    if (!in_array($role, $allowedRoles)) {
        $error = "Role tidak valid.";
    } elseif ($password !== $confirmPassword) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username sudah ada
        $check = $conn->prepare("SELECT id_users FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username sudah digunakan, silakan pilih username lain.";
        } else {
            // Simpan user
            $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (nama, username, password, role, alamat, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssss", $nama, $username, $passwordHashed, $role, $alamat);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar - Toko Risky</title>
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
      background: rgba(0, 0, 0, 0.7);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: rgb(255, 255, 255);
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
      width: 100%;
      max-width: 400px;
    }

    .form-label, .form-control {
      color: rgb(129, 126, 126);
    }

    .form-control::placeholder {
      color: rgba(139, 135, 135, 0.7);
    }

    .form-control {
      background-color: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-success {
      background-color: rgba(225, 225, 225, 0.5);
      border: none;
      color: #212529;
      font-weight: bold;
    }

    .btn-success:hover {
      background-color: rgba(225, 225, 225, 0.7);
      color: #212529;
    }

    a.text-success {
      color: rgba(225, 225, 225, 0.7) !important;
      font-weight: bold;
    }

    a.text-success:hover {
      text-decoration: underline;
    }

    #registerError {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="overlay">
    <div class="card p-4 rounded shadow">
      <h3 class="mb-4 text-center">
        <i class="bi bi-person-plus-fill"></i> Daftar Akun Baru
      </h3>

      <?php if (!empty($error)): ?>
        <div id="registerError" class="text-danger mb-3 text-center"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="register.php" method="POST" id="registerForm" novalidate>
        <div class="mb-3">
          <label for="nama" class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama lengkap Anda" required />
        </div>
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Username Anda" required />
        </div>

        <div class="mb-3">
          <label for="role" class="form-label">Pilih Role</label>
          <select class="form-control" name="role" id="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin">Admin</option>
            <option value="kasir">Kasir</option>
          </select>
      </div>


      <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" name="alamat" id="alamat" placeholder="Alamat aktif" style="resize:none;" required></textarea>
      </div>


        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Buat password" required />
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
          <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Ulangi password" required />
        </div>
        <button type="submit" class="btn btn-success w-100">Daftar</button>
      </form>

      <div class="mt-3 text-center">
        Sudah punya akun? <a href="login.php" class="text-success">Login disini</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
