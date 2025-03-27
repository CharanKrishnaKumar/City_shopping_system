<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Fetch products
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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

         /* Neon Container */
    .neon-container {
        background: rgba(0, 0, 0, 0.6); /* Semi-transparent dark background */
        padding: 5px;
        margin-top: 80px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
    }

    /* Neon Buttons */
    .neon-btn {
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
        padding: 12px 30px;
        margin: 10px;
        border: none;
        border-radius: 25px;
        color: white;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    /* Explore Stores Button */
    .neon-btn:nth-child(1) {
        background: #00ffcc; /* Neon Cyan */
        box-shadow: 0px 0px 10px #00ffcc;
    }

    /* Top Offers Button */
    .neon-btn:nth-child(2) {
        background: #ff00ff; /* Neon Pink */
        box-shadow: 0px 0px 10px #ff00ff;
    }

    /* Hover Effect */
    .neon-btn:hover {
        box-shadow: 0px 0px 20px white;
        transform: scale(1.05);
    }

        /* Adjust content to prevent overlap with fixed navbar */
        .content-container {
    flex: 1;
    overflow-y: auto;
    margin-bottom: 60px;
    padding: 20px;
}


        /* Fixed Footer */
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

        /* Transparent Container */
        .transparent-container {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    margin-top: 0 !important; /* Remove any margin */
}


        /* Product Cards */
        .card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            text-align: center;
            background-color: white;
        }

        /* Product Image */
        .card img {
            height: 150px;
            object-fit: contain;
            padding: 10px;
            border-radius: 5px;
        }

        .quantity-input {
            width: 70px;
        }
    </style>
</head>
<body>

<!-- Header -->
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
                <li class="nav-item"><a class="nav-link" href="cart.php">Basket <span class="cart-count">(<?= isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0 ?>)</span></a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="dineout.php">Dineout</a></li>
                <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
            
            <!-- Search Bar -->
            <form class="d-flex ms-3" action="search.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Search Products, Events, Dineouts..." required>
                <button class="btn btn-light" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
<!-- Neon Buttons Container -->
<div class="neon-container text-center">
    <a href="stores.php" class="btn neon-btn">Explore Stores</a>
    <a href="offers.php" class="btn neon-btn">Top Offers</a>
</div>
<!-- Main Content -->
<div class="content-container">
    <div class="container my-4">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Available Products</h2>
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
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="number" class="form-control quantity-input" name="quantity" value="1" min="1">
                                    <form class="add-to-cart-form" method="POST">
                                        <input type="hidden" name="product_id" value="<?= $row["id"]; ?>">
                                        <input type="hidden" class="hidden-quantity" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 City Mall Shopping Portal. All Rights Reserved.</p>
        <p class="mb-0">📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@pwamallshopping.com</p>
        <p>🏢 About Us: Mall Shopping Portal connects buyers with a vast range of brands, dining experiences, and events, offering a seamless shopping journey.</p>
    </div>
</footer>

<script>
$(document).ready(function() {
    $(".add-to-cart-form").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var quantityInput = form.closest(".card-body").find(".quantity-input").val();
        form.find(".hidden-quantity").val(quantityInput);
        
        $.ajax({
            url: "add_to_cart.php",
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            success: function(response) {
                if (response.cart_count !== undefined) {
                    $(".cart-count").text(`(${response.cart_count})`);
                    alert("Product added to cart!");
                    window.location.href = "cart.php";
                } else {
                    alert("Error adding product to cart.");
                }
            }
        });
    });
});
</script>

</body>
</html>
