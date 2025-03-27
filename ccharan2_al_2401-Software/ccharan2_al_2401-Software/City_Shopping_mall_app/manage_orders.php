<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all orders
$orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");

// Cancel order if requested
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_order"])) {
    $order_id = $_POST["order_id"];
    $conn->query("UPDATE orders SET status = 'Canceled' WHERE id = '$order_id'");
    echo "<script>alert('Order has been canceled.'); window.location='manage_orders.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Orders | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: url('uploads/admin_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .header {
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.8);
            padding: 15px;
            color: white;
        }
        .header img {
            height: 50px;
            margin-right: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        nav {
            background: rgba(0, 0, 0, 0.7);
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            padding: 8px 15px;
            transition: background 0.3s;
        }
        nav a:hover {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .order-table th {
            background: #333;
            color: white;
            padding: 12px;
        }
        .order-table td {
            padding: 12px;
            text-align: center;
            background: #f9f9f9;
        }
        .badge {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 5px;
        }
        .actions button {
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-cancel {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="uploads/logo.png" alt="Logo">
        <h1>Admin Dashboard</h1>
    </div>

    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_events.php">Manage Events</a>
        <a href="manage_dining.php">Manage Dining</a>
        <a href="manage_bookings.php">Manage Bookings</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Manage Orders</h2>

        <div class="table-responsive">
            <table class="table table-bordered order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User Email</th>
                        <th>Full Name</th>
                        <th>Address</th>
                        <th>Phone</th>
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
                            <td><?= htmlspecialchars($order["user_email"]); ?></td>
                            <td><?= htmlspecialchars($order["fullname"]); ?></td>
                            <td><?= htmlspecialchars($order["address"]); ?></td>
                            <td><?= htmlspecialchars($order["phone"]); ?></td>
                            <td><?= htmlspecialchars($order["items"]); ?></td>
                            <td>$<?= number_format($order["total_amount"], 2); ?></td>
                            <td><?= htmlspecialchars($order["payment_method"]); ?></td>
                            <td>
                                <span class="badge <?= $order["status"] == "Confirmed" ? 'bg-success' : 'bg-danger'; ?>">
                                    <?= $order["status"]; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($order["status"] == "Confirmed"): ?>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?= $order["id"]; ?>">
                                        <button type="submit" name="cancel_order" class="btn-cancel">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">❌ Canceled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
