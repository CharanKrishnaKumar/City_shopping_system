<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $offer_price = $_POST["offer_price"];
    $quantity = $_POST["quantity"];
    
    // Handling file upload
    $target_dir = "uploads/";
    $image = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $query = "INSERT INTO products (name, category, image, description, price, offer_price, quantity) 
              VALUES ('$name', '$category', '$image', '$description', '$price', '$offer_price', '$quantity')";
    
    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Product added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding product!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Product</title>
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
            padding: 2px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
        }
        .header img {
            width: 50px;
            margin-right: 15px;
        }
        .nav {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.9);
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .nav a:hover {
            color: #f39c12;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        .container h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #f39c12;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background: #e67e22;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="uploads/logo.png" alt="App Logo">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="nav">
        <div>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_orders.php">Manage Orders</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Add Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category" required>
            </div>
            <div class="form-group">
                <label>Image:</label>
                <input type="file" name="image" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Offer Price (Optional):</label>
                <input type="number" name="offer_price" step="0.01">
            </div>
            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" required>
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>

</body>
</html>
