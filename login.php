<?php

include 'config.php';

$error = $success = ""; 

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    // Ensure session starts only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
}
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = trim($_POST['email']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email tidak valid.';
    header('Location: login.php');
    exit();
}
        $password = trim($_POST['password']);

        if (!empty($email) && !empty($password)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];

                    // Remember Me Logic (simpan hanya email di cookie)
                    if (isset($_POST['remember'])) {
                        setcookie('email', $email, time() + (86400 * 7), "/"); // Simpan selama 7 hari
                    }

                    // Redirect
                    if ($row['role'] == 'admin') {
                        header("Location: admin_pricing.php");
                        exit();
                    } else {
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Email atau password salah.";
                }
            } else {
                $_SESSION['error'] = "Akun belum ada, silakan registrasi.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Harap isi semua field.";
        }
        header("Location: login.php");
        exit();
    }
}

// Handle Registration (Role Selalu 'user')
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $email = trim($_POST['email']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Email tidak valid.';
    header('Location: login.php');
    exit();
}
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (!empty($email) && !empty($password) && !empty($confirm_password)) {
            if ($password === $confirm_password) { // Validasi password cocok
                // Cek jika email sudah ada
                $check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $result = $check_stmt->get_result();

                if ($result->num_rows == 0) { // Jika email belum ada
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $role = 'user'; // Role hanya 'user'

                    $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $email, $hashed_password, $role);

                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Akun berhasil dibuat. Silakan login.";
                    } else {
                        $_SESSION['error'] = "Pendaftaran gagal, coba lagi.";
                    }
                    $stmt->close();
                } else {
                    $_SESSION['error'] = "Email sudah terdaftar.";
                }
                $check_stmt->close();
            } else {
                $_SESSION['error'] = "Password dan konfirmasi password harus sama.";
            }
        } else {
            $_SESSION['error'] = "Harap isi semua field.";
        }
        header("Location: login.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { width: 400px; padding: 20px; background-color: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; }
        input[type="email"], input[type="password"] { width: 100%; padding: 12px; margin: 10px -12px; border: 1px solid #ccc; border-radius: 5px; font-size: 1rem; }
        button { width: 100%; padding: 12px; background-color: #657ef8; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; transition: background-color 0.3s; }
        button:hover { background-color: #506ecf; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
    <script>
        function toggleForm(formId) {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById(formId).style.display = 'block';
        }
    </script>
</head>
<body>
    <div class="container" id="loginForm">
        <h2>Login</h2>
        <?php 
            if (isset($_SESSION['error'])) { echo "<div class='error'>{$_SESSION['error']}</div>"; unset($_SESSION['error']); }
            if (isset($_SESSION['success'])) { echo "<div class='success'>{$_SESSION['success']}</div>"; unset($_SESSION['success']); }
        ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <label>
                <input type="checkbox" name="remember" style="margin-bottom: 20px;" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>> Remember Me
            </label>
            <button type="submit" name="login">Login</button>
        </form>
        <div class="link">
            <p>Don't have an account? <a href="#" onclick="toggleForm('registerForm')">Create Account</a></p>
        </div>
    </div>

    <!-- Form Register -->
    <div class="container" id="registerForm" style="display: none;">
        <h2>Create Account</h2>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <div class="link">
            <p>Already have an account? <a href="#" onclick="toggleForm('loginForm')">Login</a></p>
        </div>
    </div>
</body>
</html>
