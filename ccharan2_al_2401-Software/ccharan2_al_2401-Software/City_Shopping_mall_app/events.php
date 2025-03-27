<?php
session_start();
include("config.php");

// Ensure user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Fetch all upcoming events (events that have not passed)
$events = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE()");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
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
            overflow: hidden;
        }

        /* Fixed Navbar */
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

        /* Content Container */
        .content-container {
            flex: 1;
            overflow-y: auto;
            margin-bottom: 60px;
            padding: 20px;
            margin-top: 80px;
        }

        /* Event Cards */
        .card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            text-align: center;
            background-color: white;
            transition: 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 20px rgba(0, 255, 255, 0.5);
        }

        .card img {
            height: 180px;
            object-fit: cover;
            border-radius: 5px 5px 0 0;
        }

        /* Book Event Button */
        .book-btn {
            background: #ff00ff;
            box-shadow: 0px 0px 10px #ff00ff;
            border-radius: 20px;
            font-weight: bold;
            color: white;
            padding: 10px 20px;
            text-transform: uppercase;
        }

        .book-btn:hover {
            background: #e600e6;
            box-shadow: 0px 0px 20px white;
        }

        /* Footer */
        .footer {
            background-color: #343a40;
            color: white;
            padding: 2px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            City Shopping Mall
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Basket</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="dineout.php">Dineout</a></li>
                <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="content-container">
    <div class="container">
        <h2 class="text-center text-light mb-4">Upcoming Events</h2>

        <div class="row">
            <?php if ($events->num_rows > 0): ?>
                <?php while ($event = $events->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="uploads/<?= htmlspecialchars($event["image"]); ?>" class="card-img-top" alt="<?= htmlspecialchars($event["title"]); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($event["title"]); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($event["description"]); ?></p>
                                <p class="card-text"><strong>Date:</strong> <?= htmlspecialchars($event["event_date"]); ?></p>
                                <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($event["location"]); ?></p>
                                <p class="card-text">
                                    <strong>Price:</strong> <del>$<?= number_format($event["price"], 2); ?></del>
                                    <?php if (!empty($event["offer_price"]) && $event["offer_price"] < $event["price"]): ?>
                                        <span class="text-success fw-bold">$<?= number_format($event["offer_price"], 2); ?></span>
                                        <span class="badge bg-danger"><?= round(100 - ($event["offer_price"] / $event["price"] * 100)) ?>% Off</span>
                                    <?php else: ?>
                                        <span class="text-muted">No Discount</span>
                                    <?php endif; ?>
                                </p>
                                <a href="book_event.php?id=<?= $event["id"]; ?>" class="btn book-btn">Book Event</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-white">No upcoming events available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p class="mb-0">© 2025 City Mall Shopping Portal. All Rights Reserved.</p>
        <p class="mb-0">📞 Contact Us: +44 123 456 7890 | ✉️ Email: support@pwamallshopping.com</p>
    </div>
</footer>

</body>
</html>
