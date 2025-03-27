<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Fetch all distinct categories from the products table
$categories = $conn->query("SELECT DISTINCT category FROM products");

$selected_category = isset($_GET["category"]) ? $_GET["category"] : "";

// Fetch products based on the selected category
$query = "SELECT * FROM products";
if ($selected_category) {
    $query .= " WHERE category = '" . $conn->real_escape_string($selected_category) . "'";
}

$products = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Stores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">
    
    <style>
        body {
            background: url('uploads/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            overflow: hidden;
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

        /* Semi-transparent container */
        .content-container {
            flex: 1;
            overflow-y: auto;
            margin-top: 80px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8); /* Transparent white */
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 95%;
            margin-left: auto;
            margin-right: auto;
        }

        .store-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .product-card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: white;
        }

        .product-card img {
            height: 150px;
            object-fit: contain;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">City Shopping Mall</a>
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

<!-- Semi-transparent Container -->
<div class="container mt-5">
    <div class="content-container">
        <h2 class="text-center text-dark mb-4">Explore Stores</h2>
        <div class="row">
            <?php while ($row = $categories->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="store-card">
                        <h5><?= $row["category"]; ?> Store</h5>
                        <a href="stores.php?category=<?= urlencode($row["category"]); ?>" class="btn btn-primary">Explore</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if ($selected_category): ?>
            <h3 class="text-dark mt-4">Products in <?= htmlspecialchars($selected_category); ?> Store</h3>
            <div class="row">
                <?php while ($row = $products->fetch_assoc()): ?>
                    <div class="col-md-3 mb-4">
                        <div class="product-card">
                            <img src="uploads/<?= $row["image"]; ?>" alt="<?= $row["name"]; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $row["name"]; ?></h5>
                                <p><strong>Price:</strong> $<?= number_format($row["price"], 2); ?></p>
                                <a href="cart.php" class="btn btn-success btn-sm">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
