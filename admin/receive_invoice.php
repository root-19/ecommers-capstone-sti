<?php
include '../components/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $purchase_order_id = $_POST['purchase_order_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    // Update Purchase Order Status
    $stmt = $conn->prepare("UPDATE purchase_orders SET status = 'Stock Received' WHERE id = ?");
    $stmt->execute([$purchase_order_id]);

    // Update Stock in Products Table
    $stmt = $conn->prepare("UPDATE products SET quantity = quantity + ? WHERE name = ?");
    $stmt->execute([$quantity, $product_name]);

    header('Location: purchase_order_history.php'); // Redirect after submission
    exit;
}
?>
