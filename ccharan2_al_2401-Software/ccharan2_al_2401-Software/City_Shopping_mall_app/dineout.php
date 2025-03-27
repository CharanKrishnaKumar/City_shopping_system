<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Fetch dining options
$dining = $conn->query("SELECT * FROM dining");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dineout Options</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">

    <style>
        /* Background with Neon Theme */
        body {
            background: url('uploads/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        /* Fixed Navbar */
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

        /* Neon Header */
        .neon-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            margin-top: 80px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
        }

        .neon-container h2 {
            color: #00ffcc;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Dining Cards */
        .card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: white;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 15px rgba(0, 255, 255, 0.7);
        }

        /* Dining Image */
        .card img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        /* Book Now Button */
        .book-now-btn {
            background: #ff00ff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            width: 100%;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .book-now-btn:hover {
            background: #d600d6;
            box-shadow: 0px 0px 10px #ff00ff;
        }

        /* Footer */
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

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            City Shopping Mall
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Basket</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Header -->
<div class="neon-container">
    <h2>Dineout Options</h2>
</div>

<!-- Dining Options -->
<div class="container mt-4">
    <div class="row">
        <?php while ($dine = $dining->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="uploads/<?= $dine["image"]; ?>" class="card-img-top" alt="<?= htmlspecialchars($dine["name"]); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($dine["name"]); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($dine["description"]); ?></p>
                        <p class="text-muted"><strong>Location:</strong> <?= htmlspecialchars($dine["location"]); ?></p>
                        <p class="fw-bold">
                            <strong>Price:</strong> 
                            <del>$<?= number_format($dine["price"], 2); ?></del>
                            <?php if (!empty($dine["offer_price"]) && $dine["offer_price"] < $dine["price"]): ?>
                                <span class="text-success fw-bold">$<?= number_format($dine["offer_price"], 2); ?></span>
                                <span class="badge bg-danger"><?= round(100 - ($dine["offer_price"] / $dine["price"] * 100)) ?>% Off</span>
                            <?php else: ?>
                                <span class="text-muted">No Discount</span>
                            <?php endif; ?>
                        </p>
                        <a href="book_dining.php?id=<?= $dine["id"]; ?>" class="btn book-now-btn">Book Now</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 City Mall Shopping Portal. All Rights Reserved.</p>
        <p>📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@pwamallshopping.com</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
