<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Mall Shopping Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">

    <style>
        body {
            background: url('uploads/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-container {
            flex: 1;
            overflow-y: auto;
            margin-top: 100px;
            margin-bottom: 80px;
            padding: 20px;
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
        .navbar .nav-link {
            color: white !important;
            font-weight: bold;
        }
        .navbar .nav-link:hover {
            color: #ddd !important;
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
        .transparent-container {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .form-label, .form-control {
            color: black !important;
            font-weight: bold;
        }
        .form-control {
            border: 2px solid black;
        }
    </style>
</head>
<body>

<!-- Navbar -->
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
                    <a class="nav-link active" href="contact.php">📞 Contact</a>
                </li>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">🚪 Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">🔑 Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">📝 Register</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin_login.php">🔧 Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="content-container">
    <div class="container">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Contact Us</h2>

            <div class="row">
                <!-- Contact Details -->
                <div class="col-md-6">
                    <h4>Mall Address</h4>
                    <p>📍 7628 Mall Street, City, London</p>
                    <p>📞 Phone: +44 123 456 7890</p>
                    <p>✉️ Email: support@mallshopping.com</p>
                    <p>🕒 Opening Hours: Mon-Sun: 10 AM - 10 PM</p>
                </div>

                <!-- Google Map -->
                <div class="col-md-6">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8354345097376!2d144.95373631590412!3d-37.81627917975144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d5df0df7fdf%3A0x5045675218ce6e0!2sMelbourne%2C+Victoria%2C+Australia!5e0!3m2!1sen!2s!4v1633522415875!5m2!1sen!2s"
                        width="100%" height="200" style="border:0; border-radius: 10px;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="mt-4">
                <h4>Contact Form</h4>
                <form action="contact_process.php" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>

            <!-- FAQs Section -->
            <div class="mt-4">
                <h4>FAQs</h4>
                <p><strong>Q: What are the mall’s operating hours?</strong></p>
                <p>A: The mall is open from 10 AM - 10 PM, Monday to Sunday.</p>

                <p><strong>Q: Is parking available?</strong></p>
                <p>A: Yes, we offer free parking for all visitors.</p>

                <p><strong>Q: How can I contact customer support?</strong></p>
                <p>A: You can call us at +123 456 7890 or email us at support@shoppingmall.com.</p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 Mall Shopping Portal. All Rights Reserved.</p>
        <p class="mb-0">📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@mallshopping.com</p>
        <p>🏢 Mall Shopping Portal connects buyers with top brands, dining experiences, and exciting events.</p>
    </div>
</footer>

</body>
</html>
