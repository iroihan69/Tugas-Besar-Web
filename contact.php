<?php
session_start(); // Memulai sesi
require_once 'config.php'; // Koneksi database

// Periksa status login jika diperlukan
$logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BYD Plus</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body style="background-color: white;">
    <!-- Navbar -->
    <header>
        <div class="navbar">
            <div class="logo">BYD</div>
            <nav>
                <ul class="menu">
                    <li>
                        <a href="index.php">Vehicles</a>
                        <div class="submenu">
                            <ul>
                                <li>
                                    <img src="./assets/menu-seal.png" alt="Seal">
                                    <a href="seal.php">Seal</a>
                                </li>
                                <li>
                                    <img src="./assets/menu-atto-3-rev2.png" alt="Atto 3">
                                    <a href="atto3.php">Atto 3</a>
                                </li>
                                <li>
                                    <img src="./assets/menu-dolphin-rev.png" alt="Dolphin">
                                    <a href="dolphin.php">Dolphin</a>
                                </li>
                                <li>
                                    <img src="./assets/menu-m6.png" alt="M6">
                                    <a href="m6.php">M6</a>
                                </li>
                            </ul>
                        </div>
                        
                    </li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Services Program</a></li>
                    <li><a href="pricing.php">Pricing</a></li>
                </ul>
            </nav>
            <div class="icons">
                <?php if ($logged_in): ?>
                    <a href="profile.php">👤 Profile</a>
                    <a href="contact.php" onclick="confirmLogout()">🚪 Logout</a>
                <?php else: ?>
                    <a href="login.php">🔑 Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <img src="./assets/contact-desktop.jpg" alt="BYD Hero" class="hero-image" style="height: 100px;"> 
    </section>
    <section class="product-announcement">
        <div class="container">
            <h2 class="title">PRODUCT ANNOUNCEMENT</h2>
            <p class="intro-text">
                Terima kasih atas kepercayaan Anda terhadap BYD.
            </p>
            <p>
                Tingkat kepuasan pelanggan yang tinggi adalah fokus utama BYD dalam mewujudkan pengalaman kepemilikan kendaraan yang menyenangkan serta keselamatan dan kenyamanan berkendara.
            </p>
            <p>
                Untuk mencapai hal tersebut, kami dari waktu ke waktu mengadakan Service Program terkait komponen yang terindikasi bermasalah secara GRATIS atau tanpa biaya. Anda cukup memasukkan 17 digit nomor rangka kendaraan (VIN) ke dalam kotak isian di bawah ini untuk mengetahui lebih lanjut.
            </p>
            <p>Terima kasih.</p>

            <!-- Formulir -->
            <form action="https://formspree.io/f/mzzbnjbb" method="POST" class="form-container">

                <div class="form-group">
                    <label for="nama">Nama pengguna <span class="required"></span></label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Anda" required>
                </div>
                <div class="form-group">
                    <label for="hp">Email pengguna <span class="required"></span></label>
                    <input type="tel" id="hp" name="hp" placeholder="Masukkan Email Anda" required>
                </div>
                <div class="form-group">
                    <label for="rangka">No. Rangka <span class="required"></span></label>
                    <input type="text" id="rangka" name="rangka" placeholder="Masukkan No. Rangka Kendaraan" maxlength="17" required>
                </div>

                <button type="submit" class="submit-btn">Cek Lokasi Nomor Rangka Kendaraan</button>
            </form>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
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

    <style>
        /* Container Utama */
        .product-announcement {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px 20px;
            background-color: #fff;
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        /* Judul */
        .product-announcement .title {
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: left;
            margin-bottom: 15px;
            font-style: italic;
        }

        /* Teks Paragraf */
        .product-announcement p {
            margin-bottom: 15px;
        }

        /* Catatan Wajib */
        .product-announcement .note {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }

        /* Kolom Input */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group .required {
            color: red;
        }

        /* Input Fields */
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #555;
        }

        /* Tombol Submit */
        .submit-btn {
            display: inline-block;
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #555;
        }

    </style>
    <script>
        function confirmLogout() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = "logout.php"; 
            }
        }
    </script>
    <script src="script.js"></script>
</body>
</html>
