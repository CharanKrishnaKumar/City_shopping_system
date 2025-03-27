<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION["user"];
$orders = $conn->query("SELECT * FROM orders WHERE user_email = '$user_email' ORDER BY order_date DESC");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_order"])) {
    $order_id = $_POST["order_id"];
    $conn->query("UPDATE orders SET status = 'Canceled' WHERE id = '$order_id' AND user_email = '$user_email'");
    echo "<script>alert('Order has been canceled.'); window.location='orders.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">
    
    <style>
        /* Background Image */
        body {
            background: url('uploads/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        /* Navbar */
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

        /* Main Content */
        .content-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            margin-top: 80px;
        }

        /* Table Styling */
        .orders-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .table th {
            background: rgba(0, 123, 255, 0.9);
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Neon Buttons */
        .neon-btn {
            font-size: 14px;
            font-weight: bold;
            padding: 8px 20px;
            border: none;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .neon-btn.cancel {
            background: #ff0000;
            box-shadow: 0px 0px 10px #ff0000;
        }
        .neon-btn.cancel:hover {
            box-shadow: 0px 0px 20px white;
            transform: scale(1.05);
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

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            PWA Shopping Mall
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Basket <span class="cart-count">(<?= isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0 ?>)</span></a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-container">
    <div class="container">
        <div class="orders-table p-4">
            <h2 class="text-center text-dark mb-4">Your Orders</h2>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= $order["id"]; ?></td>
                            <td><?= $order["items"]; ?></td>
                            <td>$<?= $order["total_amount"]; ?></td>
                            <td><?= $order["payment_method"]; ?></td>
                            <td><?= $order["status"]; ?></td>
                            <td>
                                <?php if ($order["status"] == "Confirmed"): ?>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order["id"]; ?>">
                                        <button type="submit" name="cancel_order" class="btn neon-btn cancel">Cancel Order</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-danger">Canceled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 PWA Mall Shopping Portal. All Rights Reserved.</p>
        <p class="mb-0">📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@pwamallshopping.com</p>
    </div>
</footer>

<script>
$(document).ready(function() {
    $(".neon-btn.cancel").click(function(e) {
        if (!confirm("Are you sure you want to cancel this order?")) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
