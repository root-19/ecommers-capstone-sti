<?php
include '../components/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $manufacturer = $_POST['manufacturer'];
    $type = $_POST['type'];
    $automobile_type = $_POST['automobile_type'];
    $price = $_POST['price'];
    $selling_price = $_POST['selling_price'];
    $details = $_POST['details'];
    $category = $_POST['category'];
    $new_quantity = $_POST['new_quantity'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, manufacturer = ?, type = ?, automobile_type = ?, price = ?, selling_price = ?, details = ?, category = ?, quantity = ? WHERE id = ?");
    $stmt->execute([$name, $manufacturer, $type, $automobile_type, $price, $selling_price, $details, $category, $new_quantity, $product_id]);

    header('Location: products.php'); // Redirect to products page after updating
    exit;
}
?>



