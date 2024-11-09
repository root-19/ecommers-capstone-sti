<?php

include '../components/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $default_payment_status = 'completed';
        $default_tracking_status = 'shipped';

        $update_orders = $conn->prepare("
            UPDATE `orders`
            SET payment_status = ?, tracking_status = ?
            WHERE payment_status = 'pending'
        ");
        $update_orders->execute([$default_payment_status, $default_tracking_status]);

        if ($update_orders->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No pending orders to update.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
