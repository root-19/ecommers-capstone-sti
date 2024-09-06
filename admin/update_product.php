<?php
include '../components/connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get product ID and new quantity from the form
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];

    // Ensure the new quantity is a valid number
    if (isset($product_id) && isset($new_quantity) && is_numeric($new_quantity) && $new_quantity >= 0) {
        // Update the quantity in the database
        $update_quantity = $conn->prepare("UPDATE `products` SET `quantity` = :new_quantity WHERE `id` = :product_id");
        $update_quantity->bindParam(':new_quantity', $new_quantity, PDO::PARAM_INT);
        $update_quantity->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        if ($update_quantity->execute()) {
            // Redirect back to the products page after updating
            $_SESSION['message'] = 'Quantity updated successfully!';
            header('Location: products.php');
        } else {
            $_SESSION['error'] = 'Failed to update quantity!';
            header('Location: products.php');
        }
    } else {
        $_SESSION['error'] = 'Invalid quantity!';
        header('Location: products.php');
    }
}
?>








