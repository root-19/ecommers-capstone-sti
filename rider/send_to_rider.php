<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

if (isset($_POST['send_data'])) {
    $order_id = $_POST['order_id'];

    // Fetch the canceled order details from the database
    $select_order = $conn->prepare("SELECT * FROM `canceled_orders` WHERE id = ?");
    $select_order->execute([$order_id]);
    $order_details = $select_order->fetch(PDO::FETCH_ASSOC);

    // Initialize the $message array
    $message = [];

    if ($order_details) {
        // Here, you can add your logic to send the order details to the rider
        // For example, you might use email or a messaging system to notify the rider
        // Example of a message (this can be customized):
        $messageContent = "Order ID: " . $order_details['id'] . "\n" .
                          "User ID: " . $order_details['user_id'] . "\n" .
                          "Product Quantities: " . $order_details['product_quantities'] . "\n" .
                          "Total Price: " . $order_details['total_price'] . "\n" .
                          "Payment Method: " . $order_details['method'] . "\n" .
                          "Canceled At: " . $order_details['deleted_at'];

        // Assuming you have a function to send messages
        // sendMessageToRider($messageContent);

        $message[] = 'Order details sent to the rider successfully!';
    } else {
        $message[] = 'Order not found.';
    }

    // Optional: Store messages in the session if you want to display them later
    $_SESSION['messages'] = $message; // or use another method to handle messages
}

// Redirect back to the canceled orders page
header('Location: ../admin/archive.php');
exit();
?>
