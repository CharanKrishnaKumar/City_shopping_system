<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Mall Shopping Portal</title>
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
            padding: 10px;
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
                    <a class="nav-link active" href="about.php">ℹ️ About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">📞 Contact</a>
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
            <h2 class="text-center text-dark mb-4">About Our Mall</h2>

            <div class="mb-4">
                <h4>Mall History & Vision</h4>
                <p>Established in 1995, our mall has grown into the leading shopping destination. Our vision is to provide a dynamic shopping and leisure experience with world-class brands and community engagement.</p>
            </div>

            <div class="mb-4">
                <h4>Our Mission</h4>
                <p>We aim to create an unforgettable shopping experience with outstanding customer service, a variety of stores, and eco-friendly initiatives.</p>
            </div>

            <div class="mb-4">
                <h4>Management Team</h4>
                <ul>
                    <li><strong>John Doe</strong> - Mall Director</li>
                    <li><strong>Jane Smith</strong> - Operations Manager</li>
                    <li><strong>Emily Johnson</strong> - Customer Relations Head</li>
                </ul>
            </div>

            <div class="mb-4">
                <h4>Careers</h4>
                <p>We are hiring! Join our team for roles in management, customer service, and security. Visit our <a href="careers.php">Careers Page</a> to apply.</p>
            </div>

            <div class="mb-4">
                <h4>Services Offered</h4>
                <ul>
                    <li>Spacious Parking & Valet Service</li>
                    <li>Free High-Speed Wi-Fi</li>
                    <li>Luxury Lounges & Kids Play Area</li>
                    <li>24/7 Security & Assistance</li>
                    <li>Accessibility Features for All Visitors</li>
                </ul>
            </div>

            <div class="mb-4">
                <h4>Sustainability Initiatives</h4>
                <p>Our mall is committed to eco-friendly practices, including energy-efficient lighting, waste recycling, and green architecture.</p>
            </div>

            <div class="mb-4">
                <h4>Virtual Tour (Coming Soon)</h4>
                <p>We are working on a 360-degree virtual tour for an immersive experience.</p>
            </div>

            <div class="bg-light p-3 rounded">
                <h4>Contact Information</h4>
                <p><strong>📍 Address:</strong> 123 Mall Street, City, Country</p>
                <p><strong>📞 Phone:</strong> +123 456 7890</p>
                <p><strong>✉️ Email:</strong> info@shoppingmall.com</p>
                <p><strong>🕒 Opening Hours:</strong> Mon-Sun: 10 AM - 10 PM</p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 Mall Shopping Portal. All Rights Reserved.</p>
        <p class="mb-0">📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@mallshopping.com</p>
        <p>🏢 About Us: Mall Shopping Portal connects buyers with a vast range of brands, dining experiences, and events, offering a seamless shopping journey.</p>
    </div>
</footer>

</body>
</html>
