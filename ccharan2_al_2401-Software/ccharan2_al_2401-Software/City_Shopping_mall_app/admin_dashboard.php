<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch products, events, and dining
$products = $conn->query("SELECT * FROM products");
$events = $conn->query("SELECT * FROM events");
$dining = $conn->query("SELECT * FROM dining");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        body {
            background: url('uploads/admin_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
    max-width: 1200px; /* Increased from 1000px to 1200px */
    margin: 20px auto;
    background: rgba(255, 255, 255, 0.9);
    padding: 25px; /* Slightly increased padding for better spacing */
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
}

        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background: #333;
            color: white;
            padding: 10px;
        }
        td {
            padding: 10px;
            text-align: center;
            background: #f9f9f9;
        }
        td img {
            max-width: 50px;
        }
        .actions a {
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        .edit {
            background: #28a745;
        }
        .delete {
            background: #dc3545;
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
        <h2>Manage Products</h2>
        <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Offer Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><img src="uploads/<?= $row["image"]; ?>" width="50"></td>
                    <td><?= $row["name"]; ?></td>
                    <td><?= $row["category"]; ?></td>
                    <td><?= $row["description"]; ?></td>
                    <td>$<?= $row["price"]; ?></td>
                    <td>$<?= $row["offer_price"]; ?></td>
                    <td><?= $row["quantity"]; ?></td>
                    <td class="actions">
                        <a href="edit_product.php?id=<?= $row["id"]; ?>" class="edit">Edit</a> |
                        <a href="delete_product.php?id=<?= $row["id"]; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
