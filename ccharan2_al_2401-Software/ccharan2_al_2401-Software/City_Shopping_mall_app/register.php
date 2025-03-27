<?php
include("config.php");

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize
    $name = trim($_POST["name"]);
    $fullname = trim($_POST["fullname"]);
    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure password hashing

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Email already exists! Please use a different email.";
        } else {
            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, fullname, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $password, $fullname, $address, $phone);
            if ($stmt->execute()) {
                $success_message = "Registration successful! Please login.";
            } else {
                $error_message = "Error during registration. Please try again.";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - City Shopping Mall</title>
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

        .register-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .register-container h3 {
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
                <li class="nav-item"><a class="nav-link" href="index.php">🏠 Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">ℹ️ About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">📞 Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">🔑 Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">📝 Register</a></li>
                <li class="nav-item"><a class="nav-link btn btn-warning text-dark" href="admin_login.php">⚙️ Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-container">
    <div class="register-container">
        <h3>Register</h3>
        <p class="text-muted">Create your account</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="name" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="address" placeholder="Address" required></textarea>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="mt-3 text-muted">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 Mall Shopping Portal. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>
