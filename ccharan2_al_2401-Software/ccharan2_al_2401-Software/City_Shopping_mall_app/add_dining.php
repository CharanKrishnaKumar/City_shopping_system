<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $location = $_POST["location"];
    $price = $_POST["price"];
    $offer_price = $_POST["offer_price"];

    // Image upload
    $image = "";
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
            $image = $image_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO dining (name, description, location, image, price, offer_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdd", $name, $description, $location, $image, $price, $offer_price);
    
    if ($stmt->execute()) {
        $msg = '<div class="alert success">Dining added successfully!</div>';
    } else {
        $msg = '<div class="alert error">Error adding dining. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Dining | Admin Panel</title>
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
            max-width: 600px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        .image-upload {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }
        .image-upload input {
            margin-left: 10px;
        }
        button {
            display: block;
            width: 100%;
            background: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #218838;
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
        <h2>Add Dining</h2>

        <?= $msg; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>
            
            <label>Description:</label>
            <textarea name="description" required></textarea>
            
            <label>Location:</label>
            <input type="text" name="location" required>
            
            <label>Price (£):</label>
            <input type="number" name="price" step="0.01" required>

            <label>Offer Price (£):</label>
            <input type="number" name="offer_price" step="0.01" required>

            <label>Image:</label>
            <div class="image-upload">
                <input type="file" name="image">
            </div>

            <button type="submit">Add Dining</button>
        </form>
    </div>

</body>
</html>
