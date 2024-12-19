<?php
session_start();

include 'config.php';

$email = $_SESSION['email'];
$success = $error = "";

// Cek apakah user ingin mengedit
$editMode = isset($_GET['edit']) ? true : false;

// Handle Update Account
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $new_email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($new_email) && !empty($password) && !empty($confirm_password)) {
        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE email = ?");
            $stmt->bind_param("sss", $new_email, $hashed_password, $email);
            if ($stmt->execute()) {
                $_SESSION['email'] = $new_email; // Update session email
                $success = "Akun berhasil diperbarui.";
                $email = $new_email; // Update email di halaman
                $editMode = false; // Kembali ke tampilan utama
            } else {
                $error = "Gagal memperbarui akun.";
            }
            $stmt->close();
        } else {
            $error = "Password dan konfirmasi password tidak sama.";
        }
    } else {
        $error = "Harap isi semua field.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #657ef8;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 10px;
        }
        .success { color: green; margin-bottom: 10px; }
        .error { color: red; margin-bottom: 10px; }
        a {
            text-decoration: none;
            color: #657ef8;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <?php if ($editMode): ?>
            <!-- Tampilan Edit -->
            <h1>Edit Akun</h1>
            <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>
            <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
            <form method="POST" action="">
                <label>Email Baru:</label>
                <input type="email" style="margin-left:-10px;" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <label>Password Baru:</label>
                <input type="password" style="margin-left:-10px;" name="password" placeholder="Masukkan password baru" required>
                <label>Konfirmasi Password:</label>
                <input type="password" style="margin-left:-10px;" name="confirm_password" placeholder="Ulangi password baru" required>
                <button type="submit" name="update">Simpan Perubahan</button>
                <a href="profile.php">Batal</a>
            </form>
        <?php else: ?>
            <!-- Tampilan Utama -->
            <h1>Profile Anda</h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>
            <a href="profile.php?edit=true"><button>Edit Akun</button></a>
        <?php endif; ?>
    </div>
</body>
</html>
