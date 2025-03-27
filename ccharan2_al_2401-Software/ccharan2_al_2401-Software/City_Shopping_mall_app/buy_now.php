<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to login page with a message
    header("Location: login.php?error=Please login to purchase a product.");
    exit();
}

// If logged in, proceed to checkout
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    header("Location: checkout.php?product_id=" . $product_id);
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
