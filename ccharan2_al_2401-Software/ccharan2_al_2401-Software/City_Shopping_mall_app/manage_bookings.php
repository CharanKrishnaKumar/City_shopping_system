<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch event bookings
$event_bookings = $conn->query("
    SELECT eb.id, u.name AS user_name, u.email, e.title, eb.guest_name, eb.age, eb.mobile, eb.quantity, eb.total_price, eb.booking_date
    FROM event_bookings eb
    JOIN events e ON eb.event_id = e.id
    JOIN users u ON eb.user_email = u.email
");

// Fetch dining bookings
$dining_bookings = $conn->query("
    SELECT bd.id, u.name AS user_name, u.email, d.name AS dining_name, bd.guest_name, bd.age, bd.mobile, bd.dining_date, bd.quantity, bd.total_price, bd.booking_date
    FROM booking_dining bd
    JOIN dining d ON bd.dining_id = d.id
    JOIN users u ON bd.user_email = u.email
");

// Handle cancellations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_booking"])) {
    $booking_id = intval($_POST["booking_id"]);
    $booking_type = $_POST["booking_type"];

    if ($booking_type == "event") {
        $stmt = $conn->prepare("DELETE FROM event_bookings WHERE id = ?");
    } elseif ($booking_type == "dining") {
        $stmt = $conn->prepare("DELETE FROM booking_dining WHERE id = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            echo "<script>alert('Booking cancelled successfully!'); window.location='manage_bookings.php';</script>";
        } else {
            echo "<script>alert('Failed to cancel booking.'); window.location='manage_bookings.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Bookings | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .header {
            display: flex;
            align-items: center;
            background: #343a40;
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
            background: #212529;
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
            max-width: 95%;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .search-box {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }
        .search-box input {
            width: 50%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:hover {
            background: #f2f2f2;
        }
        .cancel-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .cancel-btn:hover {
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
        <a href="manage_dining.php">Manage Dining</a>
        <a href="manage_orders.php">Manage Orders</a>
        <a href="manage_events.php">Manage Events</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Manage Bookings</h2>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search bookings...">
        </div>

        <h3>Event Bookings</h3>
        <table id="eventTable">
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Event</th>
                <th>Guest Name</th>
                <th>Age</th>
                <th>Mobile</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Booking Date</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $event_bookings->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["user_name"]); ?></td>
                    <td><?= htmlspecialchars($row["email"]); ?></td>
                    <td><?= htmlspecialchars($row["title"]); ?></td>
                    <td><?= htmlspecialchars($row["guest_name"]); ?></td>
                    <td><?= $row["age"]; ?></td>
                    <td><?= $row["mobile"]; ?></td>
                    <td><?= $row["quantity"]; ?></td>
                    <td>£<?= number_format($row["total_price"], 2); ?></td>
                    <td><?= $row["booking_date"]; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="booking_id" value="<?= $row["id"]; ?>">
                            <input type="hidden" name="booking_type" value="event">
                            <button type="submit" name="cancel_booking" class="cancel-btn" onclick="return confirm('Are you sure?')">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h3>Dining Bookings</h3>
        <table id="diningTable">
            <tr>
                <th>User</th>
                <th>Email</th>
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
            <?php while ($row = $dining_bookings->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row["user_name"]); ?></td>
                    <td><?= htmlspecialchars($row["email"]); ?></td>
                    <td><?= htmlspecialchars($row["dining_name"]); ?></td>
                    <td><?= htmlspecialchars($row["guest_name"]); ?></td>
                    <td><?= $row["age"]; ?></td>
                    <td><?= $row["mobile"]; ?></td>
                    <td><?= $row["dining_date"]; ?></td>
                    <td><?= $row["quantity"]; ?></td>
                    <td>£<?= number_format($row["total_price"], 2); ?></td>
                    <td><?= $row["booking_date"]; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="booking_id" value="<?= $row["id"]; ?>">
                            <input type="hidden" name="booking_type" value="dining">
                            <button type="submit" name="cancel_booking" class="cancel-btn" onclick="return confirm('Are you sure?')">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
