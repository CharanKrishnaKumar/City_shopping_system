<?php
session_start();
include("config.php");

$error_message = "";

// Check if user is already logged in via "Remember Me" cookie
if (isset($_COOKIE["user_email"])) {
    $_SESSION["user"] = $_COOKIE["user_email"];
    header("Location: user_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user"] = $row["email"];
            if (!empty($_POST["remember"])) {
                setcookie("user_email", $email, time() + (86400 * 30), "/");
            }
            header("Location: user_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid email or password!";
        }
    } else {
        $error_message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - City Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('uploads/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        .navbar {
            background-color: rgba(0, 123, 255, 0.9);
            padding: 15px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
        }

        .content-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 100px;
            padding-bottom: 60px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h3 {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 10px;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Header -->
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            <span style="font-size: 30px; font-weight: bold;">City Shopping Mall</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">🏠 Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">ℹ️ About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">📞 Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">🔑 Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">📝 Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">🔧 Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<!-- Main Content -->
<div class="content-container">
    <div class="login-container">
        <h3>User Login</h3>
        <p class="text-muted">Login to access your account</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label">Remember Me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="mt-3">
            <a href="forgot_password.php">Forgot Password?</a>
        </p>
        <p class="text-muted">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>&copy; 2024 City Shopping Mall. All rights reserved.</p>
</div>

</body>
</html>
