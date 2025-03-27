<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "<script>alert('Invalid event!'); window.location='events.php';</script>";
    exit();
}

$event_id = intval($_GET["id"]);
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    echo "<script>alert('Event not found!'); window.location='events.php';</script>";
    exit();
}

$final_price = (!empty($event["offer_price"]) && $event["offer_price"] < $event["price"]) ? $event["offer_price"] : $event["price"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_SESSION["user"];
    $guest_name = $_POST["guest_name"];
    $age = $_POST["age"];
    $mobile = $_POST["mobile"];
    $quantity = $_POST["quantity"];
    $total_price = $final_price * $quantity;

    $stmt = $conn->prepare("INSERT INTO event_bookings (user_email, event_id, guest_name, age, mobile, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissiid", $user_email, $event_id, $guest_name, $age, $mobile, $quantity, $total_price);

    if ($stmt->execute()) {
        echo "<script>alert('Event booked successfully!'); window.location='events.php';</script>";
    } else {
        echo "<script>alert('Failed to book event.'); window.location='events.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Event - <?= htmlspecialchars($event["title"]); ?></title>
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
            padding: 20px;
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
            padding: 10px 0;
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

        .neon-btn:nth-child(1) {
            background: #00ffcc;
            box-shadow: 0px 0px 10px #00ffcc;
        }

        .neon-btn:nth-child(2) {
            background: #ff00ff;
            box-shadow: 0px 0px 10px #ff00ff;
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
                <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-container">
    <div class="container">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Book Event - <?= htmlspecialchars($event["title"]); ?></h2>
            <p><strong>Original Price:</strong> $<?= number_format($event["price"], 2); ?></p>
            
            <?php if (!empty($event["offer_price"]) && $event["offer_price"] < $event["price"]): ?>
                <p><strong>Offer Price:</strong> $<?= number_format($event["offer_price"], 2); ?> <span class="text-success">(Discount Applied)</span></p>
            <?php else: ?>
                <p><strong>No Discount Available</strong></p>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="guest_name" class="form-label">Guest Name:</label>
                    <input type="text" class="form-control" name="guest_name" required>
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age:</label>
                    <input type="number" class="form-control" name="age" required min="1">
                </div>

                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile No:</label>
                    <input type="text" class="form-control" name="mobile" required pattern="[0-9]{10}" title="Enter a valid 10-digit number">
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" class="form-control" name="quantity" required min="1" id="quantity" onchange="updateTotal()">
                </div>

                <p><strong>Total Price:</strong> $<span id="total_price"><?= number_format($final_price, 2); ?></span></p>

                <button type="submit" class="btn btn-primary">Book Now</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>© 2025 City Mall Shopping Portal. All Rights Reserved.</p>
</footer>

<script>
    function updateTotal() {
        let pricePerTicket = <?= $final_price; ?>;
        let quantity = document.getElementById("quantity").value;
        document.getElementById("total_price").innerText = (pricePerTicket * quantity).toFixed(2);
    }
</script>

</body>
</html>
