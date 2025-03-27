<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all dining options
$dining_options = $conn->query("SELECT * FROM dining");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Dining | Admin Panel</title>
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
        .btn-add {
            display: inline-block;
            margin-bottom: 15px;
            background: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .btn-add:hover {
            background: #218838;
        }
        .dining-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .dining-table th {
            background: #333;
            color: white;
            padding: 12px;
        }
        .dining-table td {
            padding: 12px;
            text-align: center;
            background: #f9f9f9;
        }
        .dining-table img {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }
        .actions a {
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
        }
        .btn-edit {
            background: #007bff;
        }
        .btn-delete {
            background: #dc3545;
        }
        .btn-edit:hover {
            background: #0056b3;
        }
        .btn-delete:hover {
            background: #c82333;
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
        <h2>Manage Dining</h2>
        <a href="add_dining.php" class="btn-add">+ Add New Dining</a>

        <div class="table-responsive">
            <table class="table table-bordered dining-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Offer Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($dining = $dining_options->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php 
                                    $imagePath = !empty($dining["image"]) && file_exists("uploads/" . $dining["image"]) 
                                        ? "uploads/" . $dining["image"] 
                                        : "assets/no-image.png"; 
                                ?>
                                <img src="<?= $imagePath; ?>" alt="Dining Image">
                            </td>
                            <td><?= htmlspecialchars($dining["name"]); ?></td>
                            <td><?= nl2br(htmlspecialchars($dining["description"])); ?></td>
                            <td><?= htmlspecialchars($dining["location"]); ?></td>
                            <td>£<?= number_format($dining["price"], 2); ?></td>
                            <td>£<?= number_format($dining["offer_price"], 2); ?></td>
                            <td class="actions">
                                <a href="update_dining.php?id=<?= $dining["id"]; ?>" class="btn-edit">Edit</a>
                                <a href="delete_dining.php?id=<?= $dining["id"]; ?>" class="btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this dining option?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
