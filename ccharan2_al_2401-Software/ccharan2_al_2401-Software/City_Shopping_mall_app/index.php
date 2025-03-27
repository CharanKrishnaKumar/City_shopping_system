<?php
session_start();
include("config.php");

// Secure query to fetch products
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->get_result();
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Products | Mall Shopping Portal</title>
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
            padding: 2px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
        .transparent-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            text-align: center;
            background-color: white;
        }
        .card img {
            height: 150px;
            object-fit: contain;
            padding: 10px;
            border-radius: 5px;
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
                <li class="nav-item">
                    <a class="nav-link" href="index.php">🏠 Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">ℹ️ About Us</a>
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

<div class="content-container">
    <div class="container my-4">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Featured Products</h2>
            <div class="row">
                <?php while ($row = $products->fetch_assoc()): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="uploads/<?= $row["image"]; ?>" class="card-img-top" alt="<?= $row["name"]; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $row["name"]; ?></h5>
                                <p class="card-text"><strong>Category:</strong> <?= $row["category"]; ?></p>
                                <p class="card-text">
                                    <strong>Price:</strong> <del>$<?= number_format($row["price"], 2); ?></del>
                                    <?php if (!empty($row["offer_price"]) && $row["offer_price"] < $row["price"]): ?>
                                        <span class="text-success fw-bold">$<?= number_format($row["offer_price"], 2); ?></span>
                                        <span class="badge bg-danger"><?= round(100 - ($row["offer_price"] / $row["price"] * 100)) ?>% Off</span>
                                    <?php else: ?>
                                        <span class="text-muted">No Discount</span>
                                    <?php endif; ?>
                                </p>
                                <!-- Buy Now Button -->
                                <a href="buy_now.php?product_id=<?= $row['id']; ?>" class="btn btn-primary w-100">
                                    🛒 Buy Now
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 Mall Shopping Portal. All Rights Reserved.</p>
        <p class="mb-0">📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@mallshopping.com</p>
        <p>🏢 About Us: Mall Shopping Portal connects buyers with a vast range of brands, dining experiences, and events, offering a seamless shopping journey.</p>
    </div>
</footer>

</body>
</html>
