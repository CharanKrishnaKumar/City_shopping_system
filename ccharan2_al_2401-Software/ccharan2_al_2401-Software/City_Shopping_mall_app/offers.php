<?php
include("config.php");

$offers_20_50 = $conn->query("SELECT * FROM products WHERE (price - offer_price) / price * 100 BETWEEN 20 AND 50");
$offers_60_90 = $conn->query("SELECT * FROM products WHERE (price - offer_price) / price * 100 BETWEEN 60 AND 90");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Existing Offers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        .content-container {
            flex: 1;
            overflow-y: auto;
            margin-top: 80px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 95%;
            margin-left: auto;
            margin-right: auto;
        }

        .offer-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .offer-card img {
            height: 150px;
            object-fit: contain;
            padding: 10px;
            border-radius: 5px;
        }

        .footer {
            background-color: rgba(0, 123, 255, 0.9);
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">PWA Shopping Mall</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Basket</a></li>
                <li class="nav-item"><a class="nav-link" href="offers.php">Offers</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Offers Content -->
<div class="container mt-5">
    <div class="content-container">
        <h2 class="text-center text-dark mb-4">Existing Offers</h2>

        <h3 class="text-primary">20-50% Off</h3>
        <div class="row">
            <?php while ($row = $offers_20_50->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="offer-card">
                        <img src="uploads/<?= $row['image'] ?>" class="img-fluid">
                        <p><strong><?= $row['name'] ?></strong></p>
                        <p><s>$<?= number_format($row['price'], 2) ?></s> → <span class="text-success">$<?= number_format($row['offer_price'], 2) ?></span></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <h3 class="text-primary mt-4">60-90% Off</h3>
        <div class="row">
            <?php while ($row = $offers_60_90->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="offer-card">
                        <img src="uploads/<?= $row['image'] ?>" class="img-fluid">
                        <p><strong><?= $row['name'] ?></strong></p>
                        <p><s>$<?= number_format($row['price'], 2) ?></s> → <span class="text-success">$<?= number_format($row['offer_price'], 2) ?></span></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    &copy; 2024 PWA Shopping Mall. All Rights Reserved.
</div>

</body>
</html>
