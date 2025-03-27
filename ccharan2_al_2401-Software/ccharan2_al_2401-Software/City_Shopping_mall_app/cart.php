<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Handle AJAX cart update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_cart"])) {
    $id = $_POST["id"];
    $qty = max(1, (int)$_POST["quantity"]);

    if (isset($_SESSION["cart"][$id])) {
        $_SESSION["cart"][$id]["quantity"] = $qty;
    }

    $total = array_sum(array_map(function ($item) {
        $price = ($item["offer_price"] != "N/A" && $item["offer_price"] < $item["original_price"]) 
            ? $item["offer_price"] : $item["original_price"];
        return $price * $item["quantity"];
    }, $_SESSION["cart"]));

    echo json_encode(["cart_count" => count($_SESSION["cart"]), "total" => number_format($total, 2)]);
    exit();
}

// Remove item from cart
if (isset($_GET["remove"])) {
    unset($_SESSION["cart"][$_GET["remove"]]);
    header("Location: cart.php");
    exit();
}

// Clear cart
if (isset($_GET["clear"])) {
    unset($_SESSION["cart"]);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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

        .navbar .nav-link {
            color: white !important;
            font-weight: bold;
        }

        .navbar .nav-link:hover {
            color: #ddd !important;
        }

        .content-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            margin-top: 80px;
        }

        .transparent-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .cart-card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            display: flex;
            align-items: center;
            padding: 15px;
        }

        .cart-card img {
            height: 80px;
            width: 80px;
            object-fit: contain;
            margin-right: 15px;
            border-radius: 5px;
        }

        .neon-btn {
            font-size: 16px;
            font-weight: bold;
            padding: 10px 25px;
            border: none;
            border-radius: 25px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .neon-btn.clear-cart {
            background: #ff3333;
            box-shadow: 0px 0px 10px #ff3333;
        }

        .neon-btn.checkout {
            background: #00ff99;
            box-shadow: 0px 0px 10px #00ff99;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 2px 0;
            text-align: center;
            width: 100%;
            position: relative;
            bottom: 0;
            left: 0;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .social-icons a {
            color: white;
            font-size: 20px;
            margin: 0 10px;
            transition: 0.3s;
        }

        .social-icons a:hover {
            color: #00ff99;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            City Shopping Mall
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="cart.php">Basket <span class="cart-count">(<?= isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0 ?>)</span></a></li>
            <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<!-- Cart Content -->
<div class="content-container">
    <div class="container">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Shopping Cart</h2>

            <?php if (!empty($_SESSION["cart"])): ?>
                <?php $total = 0; ?>
                <?php foreach ($_SESSION["cart"] as $id => $item): ?>
                    <?php 
                        $price = ($item["offer_price"] != "N/A" && $item["offer_price"] < $item["original_price"]) ? $item["offer_price"] : $item["original_price"];
                        $subtotal = $price * $item["quantity"];
                        $total += $subtotal;
                    ?>
                    <div class="cart-card mb-3">
                        <img src="uploads/<?= $item["image"]; ?>" alt="<?= $item["name"]; ?>">
                        <div>
                            <h5><?= $item["name"]; ?></h5>
                            <p><strong>Price:</strong> $<?= number_format($price, 2); ?></p>
                            <p><strong>Subtotal:</strong> $<span class="subtotal"><?= number_format($subtotal, 2); ?></span></p>
                            <div class="d-flex align-items-center">
                                <input type="number" class="form-control quantity-input me-2" value="<?= $item["quantity"]; ?>" min="1" data-id="<?= $id; ?>">
                                <a href="cart.php?remove=<?= $id; ?>" class="btn btn-danger btn-sm">Remove</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <h4 class="text-end mt-3">Total: $<span id="total"><?= number_format($total, 2); ?></span></h4>
                <div class="text-end">
                    <a href="cart.php?clear=true" class="neon-btn clear-cart">Clear Cart</a>
                    <a href="checkout.php" class="neon-btn checkout">Proceed to Checkout</a>
                </div>
            <?php else: ?>
                <h4 class="text-center">Your cart is empty.</h4>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container text-center">
        <p class="mb-1">© 2025 city Mall Shopping Portal. All Rights Reserved.</p>
        <p class="mb-1">📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@pwamallshopping.com</p>
        <p class="mb-1">🏢 About Us: Mall Shopping Portal connects buyers with a vast range of brands, dining experiences, and events, offering a seamless shopping journey.</p>
        
        <div class="d-flex justify-content-center gap-3">
            <a href="user_dashboard.php" class="text-white">Home</a>
        
        </div>

        
    </div>
</footer>


</body>
</html>
