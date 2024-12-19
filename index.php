<?php
session_start();

header("Cache-Control: no-cache, must-revalidate, max-age=0");
header("Expires: 0");
header("Pragma: no-cache");

include 'config.php';

$logged_in = false;
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $_SESSION['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $logged_in = true;
    } else {
        session_unset();
        session_destroy();
    }
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $_SESSION['logged_in'] = true;
        $_SESSION['email'] = $email;
        header("location: index.php"); 
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
    $stmt->close();
}

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); 

    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    if ($stmt->affected_rows === 1) {
        echo "<script>alert('Registration successful');</script>";
    } else {
        echo "<script>alert('Registration failed');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYD Plus</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <header>
        <div class="navbar">
            <div class="logo">BYD</div>
            <nav>
                <ul class="menu">
                    <li>
                        <a href="#" style="text-decoration: none;">Vehicles</a>
                        <div class="submenu">
                            <ul>
                                <li>
                                    <img src="assets/menu-seal.png" alt="Seal">
                                    <a href="seal.php">Seal</a>
                                </li>
                                <li>
                                    <img src="assets/menu-atto-3-rev2.png" alt="Atto 3">
                                    <a href="atto3.php">Atto 3</a>
                                </li>
                                <li>
                                    <img src="assets/menu-dolphin-rev.png" alt="Dolphin">
                                    <a href="dolphin.php">Dolphin</a>
                                </li>
                                <li>
                                    <img src="assets/menu-m6.png" alt="M6">
                                    <a href="m6.php">M6</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="about.php" style="text-decoration: none;">About</a></li>
                    <li><a href="contact.php" style="text-decoration: none;">Services Program</a></li>
                    <li><a href="pricing.php" style="text-decoration: none;">Pricing</a></li>
                </ul>
            </nav>
            <div class="icons">
                <?php if ($logged_in): ?>
                    <a href="profile.php">👤 Profile</a>
                    <a href="index.php" onclick="confirmLogout()">🚪 Logout</a>
                <?php else: ?>
                    <a href="login.php">🔑 Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-carousel">
        <div class="carousel-track">
            <div class="carousel-item">
                <img src="./assets/4cars-home-desktop.jpg" alt="BYD Hero 1" class="hero-image">
            </div>
            <div class="carousel-item">
                <img src="./assets/9.jpg" alt="BYD Hero 2" class="hero-image">
            </div>
            <div class="carousel-item">
                <img src="./assets/product-m6-family.jpg" alt="BYD Hero 3" class="hero-image">
            </div>
        </div>
    </section>


    <!-- Car Category Section -->
    <section id="car-category">
        <h2>EXPLORE THE RANGE</h2>
        <div class="car-grid mt-4" style="padding-bottom: 10px;">
            <div class="row">
                <div class="col-md-3 text-center">
                    <img src="./assets/menu-seal.png" alt="Seal" class="img-fluid">
                    <h4 style="padding-bottom: 20px;">BYD SEAL</h4>
                    <a href="seal.php" class="btn btn-outline-dark">EXPLORE</a>
                </div>
                <div class="col-md-3 text-center">
                    <img src="./assets/menu-atto-3-rev2.png" alt="Atto3" class="img-fluid">
                    <h4 style="padding-bottom: 20px;">BYD ATTO3</h4>
                    <a href="atto3.php" class="btn btn-outline-dark">EXPLORE</a>
                </div>
                <div class="col-md-3 text-center">
                    <img src="./assets/menu-dolphin-rev.png" alt="dolphin" class="img-fluid">
                    <h4 style="padding-bottom: 20px;">BYD DOLPHIN</h4>
                    <a href="dolphin.php" class="btn btn-outline-dark">EXPLORE</a>
                </div>
                <div class="col-md-3 text-center">
                    <img src="./assets/menu-m6.png" alt="M6" class="img-fluid">
                    <h4 style="padding-bottom: 20px;">BYD M6</h4>
                    <a href="m6.php" class="btn btn-outline-dark">EXPLORE</a>
                </div>
            </div>
        </div>
    </section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 ps-0 pe-1 pt-1 pb-1">
                <div class="card bg-dark">
                    <img src="./assets/section-seal.jpg" width="720" height="603" alt="...">
                    <div class="card-img-overlay mazdaOverlayBox">
                        <h4 class="card-title mazdaMedium">BYD SEAL</h4>
                        
                                <p class="card-text">Embracing New Horizon with New Technology</p>

                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 ps-0 pe-1 pt-1 pb-1">
                <div class="card bg-dark">
                    <img src="./assets/section-dolphin-2.jpg" width="720" height="603" class="card-img" alt="...">
                    <div class="card-img-overlay mazdaOverlayBox">
                        <h4 class="card-title mazdaMedium">Minimalism at Its Finest</h4>
                        
                                <p class="card-text">Masterfully crafted with the ideal fusion of art and philosophy</p>
                        
                    </div>
                </div>
            </div>
    <style>
        /* Container Utama */
        .hero-carousel {
            position: relative;
            width: 100%;
            overflow: hidden; /* Sembunyikan bagian di luar container */
            height: 100vh; 
        }

        /* Track Carousel */
        .carousel-track {
            display: flex;
            width: 300%; /* 100% per item (3 item = 300%) */
            animation: slideLeft 20s infinite; /* Animasi ke kiri */
        }

        /* Item Individu */
        .carousel-item { 
            box-sizing: border-box;
        }

        /* Gambar di dalam Item */
        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Gambar tetap proporsional */
        }

        /* Keyframes Animasi */
        @keyframes slideLeft {
            0% { transform: translateX(0); }
            50% { transform: translateX(-66%); } 
            100% { transform: translateX(0); } 
        }

        /* Media Queries untuk Layar Kecil */
        @media (max-width: 1000px) {
            .hero-carousel {
                height: 50vh; /* Tinggi lebih kecil di perangkat mobile */
            }
        }
        @media (max-width: 768px) {
            .hero-carousel {
                height: 50vh; /* Tinggi lebih kecil di perangkat mobile */
            }
        }

        /* Media Queries untuk Layar Sangat Kecil */
        @media (max-width: 480px) {
            .hero-carousel {
                height: 20vh; /* Sesuaikan tinggi */
            }
        }

        /* Car Category Section */
        #car-category {
            padding: 40px 20px;
            background-color: white;
            text-align: center;
        }

        #car-category h2 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        /* Navigation Tabs */
        .nav-tabs {
            display: flex;
            justify-content: center;
            border-bottom: none;
            margin-bottom: 20px;
        }

        .nav-tabs .nav-item {
            margin: 0 10px;
        }

        .nav-tabs .nav-link {
            color: #555;
            font-weight: bold;
            font-size: 1rem;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active,
        .nav-tabs .nav-link:hover {
            background-color: #333;
            color: white;
        }

        /* Car Grid */
        .car-grid .row {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .car-grid .col-md-3 {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .car-grid .col-md-3:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .car-grid img {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .car-grid h4 {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .car-grid p {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 15px;
        }

        /* Explore Button */
        .car-grid .btn-outline-dark {
            text-decoration: none;
            background-color: white;
            color: black;
            border: 2px solid #333;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .car-grid .btn-outline-dark:hover {
            background-color: #333;
            color: #fff;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .car-grid .row {
                flex-direction: column;
                align-items: center;
            }

            .car-grid .col-md-3 {
                width: 80%;
                margin-bottom: 20px;
            }
        }

        /* General Container */
        .container-fluid {
            padding: 0; /* Hapus padding default */
        }

        .row {
            margin: 0; /* Hilangkan margin row */
        }

        /* Card Styling */
        .card {
            position: relative;
            border: none;
            border-radius: 0; /* Hilangkan border radius */
            overflow: hidden;
        }

        .card img {
            width: 100%;
            height: auto;
            object-fit: cover; /* Gambar tetap proporsional */
            transition: transform 0.3s ease;
        }

        .card:hover img {
            transform: scale(1.1); /* Efek zoom gambar saat hover */
        }

        .card-img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.4); /* Overlay gelap */
            text-align: center;
            padding: 1rem;
            transition: background 0.3s ease;
        }

        .card:hover .card-img-overlay {
            background: rgba(0, 0, 0, 0.6); /* Overlay lebih gelap saat hover */
        }

        .card-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #fff;
            text-transform: uppercase;
        }

        .card-text {
            font-size: 1rem;
            color: #ddd;
            margin-bottom: 1.5rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .card {
                margin-bottom: 1rem;
            }
        }

    </style>
     <!-- Footer -->
     <footer class="footer">
        <div class="footer-container">
            <!-- Kebijakan -->
            <div class="footer-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Ketentuan Penggunaan</a>
                <a href="#">Kuki</a>
            </div>
            <!-- Media Sosial -->
            <div class="footer-social">
                <span>IKUTI KAMI</span>
                <a href="#"><img src="./assets/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="./assets/instagram (1).png" alt="Instagram"></a>
                <a href="#"><img src="./assets/youtube.png" alt="YouTube"></a>
                <a href="#"><img src="./assets/tik-tok.png" alt="TikTok"></a>
            </div>
        </div>
        <!-- Hak Cipta -->
        <div class="footer-bottom">
            <hr>
            <p>PT BYD Motor Indonesia. Hak cipta dilindungi undang-undang.</p>
        </div>
    </footer>

    <script>
        function confirmLogout() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
