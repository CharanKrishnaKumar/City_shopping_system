<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Redirect if the cart is empty
if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo "<script>alert('Your cart is empty!'); window.location='user_dashboard.php';</script>";
    exit();
}

// Fetch user details
$user_email = $_SESSION["user"];
$user_query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$user_query->bind_param("s", $user_email);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

$fullname = $user["fullname"] ?? "";
$address = $user["address"] ?? "";
$phone = $user["phone"] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["place_order"])) {
    $fullname = trim($_POST["fullname"]);
    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);
    $payment_method = $_POST["payment_method"];

    if (empty($fullname) || empty($address) || empty($phone) || empty($payment_method)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else {
        $total_amount = 0;
        $order_items = [];

        foreach ($_SESSION["cart"] as $id => $item) {
            $price = ($item["offer_price"] !== "N/A" && $item["offer_price"] < $item["original_price"]) 
                        ? $item["offer_price"] 
                        : $item["original_price"];
            $subtotal = $price * $item["quantity"];
            $total_amount += $subtotal;
            $order_items[] = "{$item["name"]} (x{$item["quantity"]}) - \${$subtotal}";
        }

        $order_items_str = implode(", ", $order_items);
        $order_date = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("INSERT INTO orders (user_email, fullname, address, phone, items, total_amount, payment_method, order_date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdss", $user_email, $fullname, $address, $phone, $order_items_str, $total_amount, $payment_method, $order_date);
        $stmt->execute();

        // Clear cart after order placement
        unset($_SESSION["cart"]);

        echo "<script>alert('Order placed successfully!'); window.location='user_dashboard.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout | Mall Shopping</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .checkout-container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .order-summary th, .order-summary td {
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">🏬 Mall Shopping</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user_dashboard.php">🏠 Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">🛒 Your Basket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">🚪 Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Checkout Container -->
<div class="container checkout-container">
    <h2 class="text-center mb-4">🛍️ Checkout</h2>

    <!-- Order Summary -->
    <h4>Order Summary</h4>
    <table class="table table-bordered order-summary">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Original Price</th>
                <th>Offer Price</th>
                <th>Discount</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($_SESSION["cart"] as $id => $item): ?>
                <?php 
                    $price = ($item["offer_price"] !== "N/A" && $item["offer_price"] < $item["original_price"]) 
                                ? $item["offer_price"] 
                                : $item["original_price"];
                    $subtotal = $price * $item["quantity"];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item["name"]); ?></td>
                    <td><?= $item["quantity"]; ?></td>
                    <td>$<?= $item["original_price"]; ?></td>
                    <td><?= $item["offer_price"] !== "N/A" ? "$" . $item["offer_price"] : "-"; ?></td>
                    <td>
                        <?= ($item["offer_price"] !== "N/A" && $item["offer_price"] < $item["original_price"]) 
                            ? round((($item["original_price"] - $item["offer_price"]) / $item["original_price"]) * 100, 2) . "%" 
                            : "-"; ?>
                    </td>
                    <td>$<?= number_format($subtotal, 2); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5"><strong>Total:</strong></td>
                <td><strong>$<?= number_format($total, 2); ?></strong></td>
            </tr>
        </tbody>
    </table>

    <!-- Billing Details Form -->
    <h4>Billing Details</h4>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name:</label>
            <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($fullname); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Address:</label>
            <textarea class="form-control" name="address" required><?= htmlspecialchars($address); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone); ?>" required>
        </div>
        <h5>Payment Method</h5>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="Credit Card" required>
            <label class="form-check-label">💳 Credit Card</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="PayPal">
            <label class="form-check-label">💰 PayPal</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" value="Cash on Delivery">
            <label class="form-check-label">🚚 Cash on Delivery</label>
        </div>
        <button type="submit" class="btn btn-success mt-3 w-100" name="place_order">✅ Place Order</button>
    </form>
</div>

</body>
</html>
