<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $offer_price = $_POST["offer_price"];
    $quantity = $_POST["quantity"];

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $query = "UPDATE products SET name='$name', category='$category', image='$image', description='$description', price='$price', offer_price='$offer_price', quantity='$quantity' WHERE id=$id";
    } else {
        $query = "UPDATE products SET name='$name', category='$category', description='$description', price='$price', offer_price='$offer_price', quantity='$quantity' WHERE id=$id";
    }

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Product updated successfully!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating product!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
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
    position: fixed;
    top: 0;
    width: 100%;
    height: 60px;
    display: flex;
    align-items: center;
    padding: 10px 20px;
    background: rgba(0, 0, 0, 0.9);
    color: white;
    z-index: 1000;
}
        .header img {
            width: 50px;
            margin-right: 15px;
        }
        .nav {
            position: fixed;
            top: 81px; /* Positioned right below the header */
            width: 100%;
            display: flex;
            justify-content: center;
            background: rgba(0, 0, 0, 0.85);
            padding: 12px 0;
            z-index: 999;
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
            margin: 140px auto 50px auto; /* Adjusted margin to avoid header/navbar overlap */
            background: rgba(255, 255, 255, 0.95);
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
        .product-image {
            display: block;
            margin: 10px auto;
            max-width: 100px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="uploads/logo.png" alt="App Logo">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="nav">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_dashboard.php">Manage Products</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id']; ?>">
            
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" value="<?= $product['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <input type="text" name="category" value="<?= $product['category']; ?>" required>
            </div>
            <div class="form-group">
                <label>Current Image:</label>
                <img src="uploads/<?= $product['image']; ?>" class="product-image">
            </div>
            <div class="form-group">
                <label>New Image (Optional):</label>
                <input type="file" name="image">
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required><?= $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Price:</label>
                <input type="number" name="price" value="<?= $product['price']; ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Offer Price (Optional):</label>
                <input type="number" name="offer_price" value="<?= $product['offer_price']; ?>" step="0.01">
            </div>
            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" value="<?= $product['quantity']; ?>" required>
            </div>
            <button type="submit">Update Product</button>
        </form>
    </div>

</body>
</html>
