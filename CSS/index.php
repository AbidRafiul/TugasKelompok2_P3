

<?php
include 'confiig/conn.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Beranda - Toko Risky Bawang Palawija</title>
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

    .overlay {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6));
      min-height: 100vh;
      padding-top: 60px;
    }

    .hero-text {
      text-align: center;
      padding: 120px 20px 80px;
      text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.7);
    }

    .hero-text h1 {
      font-size: 3.2rem;
      font-weight: bold;
      margin-bottom: 20px;
      animation: fadeInDown 1s ease-in-out;
    }

    .hero-text p {
      font-size: 1.3rem;
      line-height: 1.6;
      max-width: 800px;
      margin: auto;
      animation: fadeInUp 1.2s ease-in-out;
    }

    .btn-primary {
      background-color: #ffc107;
      border: none;
      color: #212529;
      font-weight: bold;
      padding: 12px 28px;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #e0a800;
    }

    footer {
      background-color: rgba(0, 0, 0, 0.85);
      color: #ccc;
      text-align: center;
      padding: 20px;
      font-size: 0.9rem;
    }

    /* Animations */
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .section-about {
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(5px);
      padding: 50px 20px;
      text-align: center;
      border-top: 1px solid rgba(255,255,255,0.2);
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .section-about h2 {
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .section-about p {
      max-width: 700px;
      margin: auto;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>

  <div class="overlay">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
      <div class="container">
        <a class="navbar-brand fw-bold" href="#"><i class="bi bi-shop"></i> Toko Risky</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="container hero-text">
      <h1>Selamat Datang di Toko Risky Bawang Palawija</h1>
      <p class="mt-3">Toko terpercaya Anda dalam pengadaan <strong>rempah-rempah</strong> berkualitas di Madiun dan sekitarnya.<br>
        Kami menyediakan bawang, kunyit, jahe, lengkuas, dan berbagai kebutuhan palawija lainnya secara lengkap dan terpercaya.</p>
      <a href="login.php" class="btn btn-primary btn-lg mt-4"><i class="bi bi-box-arrow-right"></i> Masuk</a>
    </div>

    <!-- About Section -->
    <div class="section-about">
      <h2>Tentang Toko Risky</h2>
      <p>Kami berkomitmen menyediakan produk rempah yang segar dan berkualitas tinggi untuk kebutuhan rumah tangga, bisnis kuliner, dan distributor. Dengan pengalaman bertahun-tahun dalam distribusi palawija, kami adalah mitra terpercaya Anda.</p>
    </div>

    <!-- Footer -->
    <footer>
      <p>&copy; 2025 Toko Risky Bawang Palawija. All rights reserved.</p>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
?>