<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION["user"];

// Fetch event bookings
$event_bookings = $conn->prepare("
    SELECT eb.id, e.title, eb.guest_name, eb.age, eb.mobile, eb.quantity, eb.total_price, eb.booking_date
    FROM event_bookings eb
    JOIN events e ON eb.event_id = e.id
    WHERE eb.user_email = ?
");
$event_bookings->bind_param("s", $user_email);
$event_bookings->execute();
$event_results = $event_bookings->get_result();

// Fetch dining bookings
$dining_bookings = $conn->prepare("
    SELECT bd.id, d.name, bd.guest_name, bd.age, bd.mobile, bd.dining_date, bd.quantity, bd.total_price, bd.booking_date
    FROM booking_dining bd
    JOIN dining d ON bd.dining_id = d.id
    WHERE bd.user_email = ?
");
$dining_bookings->bind_param("s", $user_email);
$dining_bookings->execute();
$dining_results = $dining_bookings->get_result();

// Handle cancellations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_booking"])) {
    $booking_id = intval($_POST["booking_id"]);
    $booking_type = $_POST["booking_type"];

    if ($booking_type == "event") {
        $stmt = $conn->prepare("DELETE FROM event_bookings WHERE id = ? AND user_email = ?");
    } elseif ($booking_type == "dining") {
        $stmt = $conn->prepare("DELETE FROM booking_dining WHERE id = ? AND user_email = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("is", $booking_id, $user_email);
        if ($stmt->execute()) {
            echo "<script>alert('Booking cancelled successfully!'); window.location='bookings.php';</script>";
        } else {
            echo "<script>alert('Failed to cancel booking.'); window.location='bookings.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
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
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: bold;
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

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
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

        .btn-cancel {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-cancel:hover {
            background-color: darkred;
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
            <h2 class="text-center text-dark mb-4">Your Bookings</h2>

            <h4 class="text-primary">Event Bookings</h4>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Event</th>
                        <th>Guest Name</th>
                        <th>Age</th>
                        <th>Mobile</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Booking Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $event_results->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["title"]); ?></td>
                            <td><?= htmlspecialchars($row["guest_name"]); ?></td>
                            <td><?= $row["age"]; ?></td>
                            <td><?= $row["mobile"]; ?></td>
                            <td><?= $row["quantity"]; ?></td>
                            <td>$<?= number_format($row["total_price"], 2); ?></td>
                            <td><?= $row["booking_date"]; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $row["id"]; ?>">
                                    <input type="hidden" name="booking_type" value="event">
                                    <button type="submit" name="cancel_booking" class="btn-cancel">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h4 class="text-success mt-4">Dining Bookings</h4>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Dining</th>
                        <th>Guest Name</th>
                        <th>Age</th>
                        <th>Mobile</th>
                        <th>Dining Date</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Booking Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $dining_results->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["name"]); ?></td>
                            <td><?= htmlspecialchars($row["guest_name"]); ?></td>
                            <td><?= $row["age"]; ?></td>
                            <td><?= $row["mobile"]; ?></td>
                            <td><?= $row["dining_date"]; ?></td>
                            <td><?= $row["quantity"]; ?></td>
                            <td>$<?= number_format($row["total_price"], 2); ?></td>
                            <td><?= $row["booking_date"]; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $row["id"]; ?>">
                                    <input type="hidden" name="booking_type" value="dining">
                                    <button type="submit" name="cancel_booking" class="btn-cancel">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>© 2025 PWA Mall Shopping Portal. All Rights Reserved.</p>
</footer>

</body>
</html>
