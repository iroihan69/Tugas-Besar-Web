<?php

include 'config.php';

// Ambil data dari tabel cars
$sql = "SELECT name, price, torque, power, `range`, battery, acceleration, image FROM cars"; // Menambahkan kolom image
$result = $conn->query($sql);
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
        <div class="navbar" style="background-color: black;">
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
                <a href="login.php">👤</a>
            </div>
        </div>
    </header>

    <section class="price-section">
        <h1 class="price-title">Daftar Harga</h1>
        <hr>

        <!-- Tabel Harga -->
        <div class="price-container">
        <div class="card-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <?php
                        // Cek apakah gambar ada, jika tidak setel gambar default
                        $imagePath = !empty($row['image']) ? "./assets/" . htmlspecialchars($row['image']) : './assets/default-image.png';
                        ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <h3 class="car-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="price"><strong><?php echo htmlspecialchars($row['price']); ?></strong></p>
                        <p class="info"><strong><?php echo htmlspecialchars($row['torque']); ?></strong><br>Torsi Maksimum</p>
                        <p class="info"><strong><?php echo htmlspecialchars($row['power']); ?></strong><br>Daya Maksimum</p>
                        <p class="info"><strong><?php echo htmlspecialchars($row['range']); ?></strong><br>Jarak Mengemudi</p>
                        <p class="info"><strong><?php echo htmlspecialchars($row['battery']); ?></strong><br>Kapasitas Baterai</p>
                        <p class="info"><strong><?php echo htmlspecialchars($row['acceleration']); ?></strong><br>0-100 km/h</p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada data tersedia</p>
            <?php endif; ?>
        </div>
    </div>

    </section>

    <style>
        /* Container Utama */
.price-container {
    text-align: center;
    padding: 20px;
    background-color: white;
}

.price-container .title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

/* Grid Container */
.card-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

/* Card Styling */
.card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 250px;
    padding: 15px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.card img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 10px;
}

.card .car-name {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.card .price {
    font-size: 1.1rem;
    font-weight: bold;
    color: #000;
    margin-bottom: 10px;
}

.card .info {
    font-size: 0.9rem;
    color: #555;
    line-height: 1.6;
}

/* Responsif */
@media (max-width: 768px) {
    .card-container {
        flex-direction: column;
        align-items: center;
    }

    .card {
        width: 90%;
    }
}

    </style>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
