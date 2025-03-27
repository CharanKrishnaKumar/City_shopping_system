<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET["id"];
$result = $conn->query("SELECT * FROM events WHERE id = $id");
$event = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $event_date = $_POST["event_date"];
    $location = $_POST["location"];
    $price = $_POST["price"];
    $offer_price = $_POST["offer_price"];

    $imagePath = $event["image"];

    if (!empty($_FILES["image"]["name"])) {
        $target = "uploads/" . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $imagePath = $target;
        }
    }

    $sql = "UPDATE events SET title='$title', description='$description', event_date='$event_date', location='$location', 
            image='$imagePath', price='$price', offer_price='$offer_price' WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: manage_events.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Event | Admin Panel</title>
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
            max-width: 600px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .btn-submit {
            width: 100%;
            background: #007bff;
            color: white;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            border: none;
        }
        .btn-submit:hover {
            background: #0056b3;
        }
        .image-preview {
            display: block;
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
        <a href="manage_events.php">Manage Events</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Update Event</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($event["title"]); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" required><?= htmlspecialchars($event["description"]); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" name="event_date" value="<?= $event["event_date"]; ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Location:</label>
                <input type="text" name="location" value="<?= htmlspecialchars($event["location"]); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (£):</label>
                <input type="number" name="price" step="0.01" value="<?= $event["price"]; ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Offer Price (£):</label>
                <input type="number" name="offer_price" step="0.01" value="<?= $event["offer_price"]; ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image:</label>
                <img id="currentImage" class="image-preview" src="<?= $event["image"]; ?>" alt="Event Image">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload New Image:</label>
                <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(event)">
                <img id="imagePreview" class="image-preview" src="assets/no-image.png" style="display:none;">
            </div>

            <button type="submit" class="btn-submit">Update Event</button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function () {
                var currentImg = document.getElementById('currentImage');
                var previewImg = document.getElementById('imagePreview');
                previewImg.src = reader.result;
                previewImg.style.display = 'block';
                currentImg.style.display = 'none';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>
</html>
