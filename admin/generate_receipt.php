<?php
include '../components/connect.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details from the purchase table
    $stmt = $conn->prepare("SELECT * FROM purchase WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $purchase = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($purchase) {
        // Generate receipt content (you can customize this as needed)
        echo '<h1>Receipt for Purchase</h1>';
        echo '<p>Product ID: ' . htmlspecialchars($purchase['product_id']) . '</p>';
        echo '<p>Name: ' . htmlspecialchars($purchase['name']) . '</p>';
        echo '<p>Manufacturer: ' . htmlspecialchars($purchase['manufacturer']) . '</p>';
        echo '<p>Type: ' . htmlspecialchars($purchase['type']) . '</p>';
        echo '<p>Automobile Type: ' . htmlspecialchars($purchase['automobile_type']) . '</p>';
        echo '<p>Category: ' . htmlspecialchars($purchase['category']) . '</p>';
        echo '<p>Quantity: ' . htmlspecialchars($purchase['quantity']) . '</p>';
        echo '<p>Purchase Date: ' . htmlspecialchars($purchase['purchase_date']) . '</p>';

        // Additional formatting for receipt can be added here
    } else {
        echo 'No purchase found.';
    }
}
?>
