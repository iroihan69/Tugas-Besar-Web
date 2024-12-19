<?php
session_start();

// Periksa apakah pengguna sudah login dan role-nya admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Variabel untuk prefill form
$edit_id = $name = $price = $torque = $power = $range = $battery = $acceleration = $image = "";

// Prefill form ketika tombol Edit ditekan
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if ($result_edit->num_rows > 0) {
        $row = $result_edit->fetch_assoc();
        $name = $row['name'];
        $price = $row['price'];
        $torque = $row['torque'];
        $power = $row['power'];
        $range = $row['range'];
        $battery = $row['battery'];
        $acceleration = $row['acceleration'];
        $image = $row['image']; // Menyimpan path gambar jika ada
    }
    $stmt->close();
}

// CRUD Logic
if (isset($_POST['create'])) {
    // Mendapatkan data dari form
    $name = $_POST['name'];
    $price = $_POST['price'];
    $torque = $_POST['torque'];
    $power = $_POST['power'];
    $range = $_POST['range'];
    $battery = $_POST['battery'];
    $acceleration = $_POST['acceleration'];
    $image = ''; // Default gambar kosong

    // Menangani upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "assets/"; // Folder penyimpanan gambar
        $image_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Cek apakah file gambar valid (hanya jpg, png, jpeg, gif)
        if (in_array($image_type, ['jpg', 'png', 'jpeg', 'gif'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $image_name; // Nama file gambar yang disimpan
            }
        }
    }

    // Cek jika gambar kosong saat edit
    if (isset($_GET['edit']) && empty($image)) {
        echo "<script>alert('Gambar wajib diupload saat mengedit!');</script>";
    } else {
        // Jika form sedang update
        if (!empty($_POST['id'])) {
            $stmt = $conn->prepare("UPDATE cars SET name=?, price=?, torque=?, power=?, `range`=?, battery=?, acceleration=?, image=? WHERE id=?");
            $stmt->bind_param("ssssssssi", $name, $price, $torque, $power, $range, $battery, $acceleration, $image, $_POST['id']);
        } else {
            $stmt = $conn->prepare("INSERT INTO cars (name, price, torque, power, `range`, battery, acceleration, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $name, $price, $torque, $power, $range, $battery, $acceleration, $image);
        }
        $stmt->execute();
        header("Location: admin_pricing.php");
        exit();
    }
}
elseif (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM cars WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: admin_pricing.php");
    exit();
}

// Ambil data
$result = $conn->query("SELECT * FROM cars");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Daftar Harga</title>
    <style>
        /* Reset Style */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f9f9f9; color: #333; line-height: 1.6; }
        h1 { text-align: center; margin: 20px 0; font-size: 2.2rem; color: #2c3e50; }

        /* Container */
        .container { width: 90%; margin: 20px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }

        form { margin-bottom: 20px; padding: 20px; background: #f4f4f4; border-radius: 10px; }
        form h3 { margin-bottom: 10px; font-size: 1.5rem; }
        form label { font-weight: bold; margin-bottom: 5px; display: block; }
        form input, form textarea { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px 15px; margin-right: 10px; border: none; border-radius: 5px; cursor: pointer; color: #fff; }
        .btn-primary { background-color: #28a745; }
        .btn-secondary { background-color: #6c757d; }
        button:hover { opacity: 0.8; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        table th, table td { padding: 12px; text-align: center; border: 1px solid #ddd; }
        table th { background-color: #343a40; color: white; }
        table tr:nth-child(even) { background-color: #f2f2f2; }
        table tr:hover { background-color: #e2e6ea; }
        .action-btn { margin: 0 5px; padding: 8px 12px; border-radius: 5px; color: #fff; text-decoration: none; }
        .edit-btn { background-color: #007bff; }
        .delete-btn { background-color: #dc3545; }
        .logout-btn {
            display: block;
            margin: 10px auto;
            width: fit-content;
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .logout-btn:hover {
            background-color: #c82333;
            opacity: 0.9;
        }
        /* Gambar Mobil */
        form input[type="file"] {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        table img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Admin - Daftar Harga Mobil</h1>
    <a href="logout.php" class="logout-btn">Keluar</a> <!-- Tombol Logout -->
    <div class="container">
        <!-- Form Tambah Data -->
        <form method="POST" enctype="multipart/form-data">
            <h3><?= isset($_GET['edit']) ? "Edit Data" : "Tambah Data" ?></h3>
            <input type="hidden" name="id" value="<?= $edit_id ?>">
            <label for="name">Nama Mobil:</label>
            <input type="text" name="name" value="<?= $name ?>" required>

            <label for="price">Harga:</label>
            <input type="text" name="price" value="<?= $price ?>" required>

            <label for="torque">Torsi:</label>
            <input type="text" name="torque" value="<?= $torque ?>" required>

            <label for="power">Daya:</label>
            <input type="text" name="power" value="<?= $power ?>" required>

            <label for="range">Jarak:</label>
            <input type="text" name="range" value="<?= $range ?>" required>

            <label for="battery">Baterai:</label>
            <input type="text" name="battery" value="<?= $battery ?>" required>

            <label for="acceleration">0-100 km/h:</label>
            <input type="text" name="acceleration" value="<?= $acceleration ?>" required>

            <!-- Input Gambar -->
            <label for="image">Gambar Mobil:</label>
            <input type="file" name="image" accept="image/*" <?= !isset($_GET['edit']) ? 'required' : '' ?>>

            <button type="submit" name="create" class="btn-primary"><?= isset($_GET['edit']) ? "Update" : "Tambah" ?></button>
        </form>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Torsi</th>
                    <th>Daya</th>
                    <th>Jarak</th>
                    <th>Baterai</th>
                    <th>0-100 km/h</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td><?= $row['torque'] ?></td>
                    <td><?= $row['power'] ?></td>
                    <td><?= $row['range'] ?></td>
                    <td><?= $row['battery'] ?></td>
                    <td><?= $row['acceleration'] ?></td>
                    <td><img src="assets/<?= $row['image'] ?>" alt="Gambar <?= $row['name'] ?>"></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" class="action-btn edit-btn">Edit</a>
                        <a href="?delete=<?= $row['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
