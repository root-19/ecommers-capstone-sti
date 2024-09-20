<?php
include '../components/connect.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    $stmt = $conn->prepare("UPDATE products SET quantity = ? WHERE id = ?");
    $stmt->execute([$new_quantity, $product_id]);

    header('Location: products.php'); // Redirect to products page after updating
    exit;
}
?>




