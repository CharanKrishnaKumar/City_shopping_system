<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "<script>alert('Invalid dining selection!'); window.location='dineout.php';</script>";
    exit();
}

$dining_id = intval($_GET["id"]);
$stmt = $conn->prepare("SELECT * FROM dining WHERE id = ?");
$stmt->bind_param("i", $dining_id);
$stmt->execute();
$result = $stmt->get_result();
$dining = $result->fetch_assoc();

if (!$dining) {
    echo "<script>alert('Dining option not found!'); window.location='dineout.php';</script>";
    exit();
}

$final_price = (!empty($dining["offer_price"]) && $dining["offer_price"] < $dining["price"]) ? $dining["offer_price"] : $dining["price"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_SESSION["user"];
    $guest_name = $_POST["guest_name"];
    $age = $_POST["age"];
    $mobile = $_POST["mobile"];
    $dining_date = $_POST["dining_date"];
    $quantity = $_POST["quantity"];
    $total_price = $final_price * $quantity;

    if (empty($dining_date)) {
        echo "<script>alert('Dining date is required!'); window.location='book_dining.php?id=$dining_id';</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO booking_dining (user_email, dining_id, guest_name, age, mobile, dining_date, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissssdi", $user_email, $dining_id, $guest_name, $age, $mobile, $dining_date, $quantity, $total_price);

    if ($stmt->execute()) {
        echo "<script>alert('Dining booked successfully!'); window.location='dineout.php';</script>";
    } else {
        echo "<script>alert('Failed to book dining.'); window.location='dineout.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Dining - <?= htmlspecialchars($dining["name"]); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: url('uploads/background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

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

        .content-container {
            flex: 1;
            overflow-y: auto;
            margin-top: 80px;
            padding: 10px;
        }

        .transparent-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 5px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .neon-btn {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            display: inline-block;
            text-decoration: none;
        }

        .neon-btn:hover {
            box-shadow: 0px 0px 20px white;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            City Shopping Mall
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="dineout.php">Dining</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-container">
    <div class="container">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Book Dining - <?= htmlspecialchars($dining["name"]); ?></h2>
            <p><strong>Original Price:</strong> $<?= number_format($dining["price"], 2); ?></p>

            <?php if (!empty($dining["offer_price"]) && $dining["offer_price"] < $dining["price"]): ?>
                <p><strong>Offer Price:</strong> $<?= number_format($dining["offer_price"], 2); ?> <span class="text-success">(Discount Applied)</span></p>
            <?php else: ?>
                <p><strong>No Discount Available</strong></p>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Guest Name:</label>
                    <input type="text" class="form-control" name="guest_name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Age:</label>
                    <input type="number" class="form-control" name="age" required min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile No:</label>
                    <input type="text" class="form-control" name="mobile" required pattern="[0-9]{10}" title="Enter a valid 10-digit number">
                </div>

                <div class="mb-3">
                    <label class="form-label">Dining Date:</label>
                    <input type="date" class="form-control" name="dining_date" required min="<?= date('Y-m-d'); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity:</label>
                    <input type="number" class="form-control" name="quantity" id="quantity" required min="1" value="1">
                </div>

                <p><strong>Total Price:</strong> $<span id="total_price"><?= number_format($final_price, 2); ?></span></p>

                <button type="submit" class="btn btn-primary">Book Now</button>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    <p>© 2025 City Mall Shopping Portal. All Rights Reserved.</p>
</footer>

<script>
    document.getElementById("quantity").addEventListener("input", function() {
        document.getElementById("total_price").innerText = (<?= $final_price; ?> * this.value).toFixed(2);
    });
</script>

</body>
</html>
