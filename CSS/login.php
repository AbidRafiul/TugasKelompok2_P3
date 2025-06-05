<?php
session_start();
include 'confiig/conn.php';


if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['id_users'] = $user['id_users'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Toko Risky</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
      height: 100vh;
    }

    .overlay {
      background: rgba(0, 0, 0, 0.7);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: white;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
      width: 100%;
      max-width: 400px;
      padding: 1.5rem;
      border-radius: 0.5rem;
    }

    .form-label, .form-control {
      color: white;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .form-control {
      background-color: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-success {
      background-color: rgba(255, 255, 255, 0.9);
      border: none;
      color: #212529;
      font-weight: bold;
    }

    .btn-success:hover {
      background-color: rgba(225, 225, 225, 0.6);
      color: #212529;
    }

    a.text-success {
      color: rgba(225, 225, 225, 0.7) !important;
      font-weight: bold;
    }

    a.text-success:hover {
      text-decoration: underline;
    }

    #loginError {
      font-weight: bold;
      margin-top: 1rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="overlay">
    <div class="card">
      <h3 class="mb-4 text-center">
        <i class="bi bi-box-arrow-in-right"></i> Login Toko Risky
      </h3>

      <?php if(!empty($error)): ?>
        <div id="loginError" class="text-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="" novalidate>
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            class="form-control"
            name="username"
            id="username"
            placeholder="Masukkan username"
            required
            autofocus
          />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            type="password"
            class="form-control"
            name="password"
            id="password"
            placeholder="Masukkan password"
            required
          />
        </div>
        <button type="submit" class="btn btn-success w-100">Login</button>
      </form>

      <div class="mt-3 text-center">
        Belum punya akun? <a href="register.php" class="text-success">Daftar disini</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
