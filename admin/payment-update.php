


<?php

include '../components/connect.php';

if (isset($_POST['order_id']) && isset($_POST['payment_status'])) {
    $order_id = $_POST['order_id'];
    $payment_status = filter_var($_POST['payment_status'], FILTER_SANITIZE_STRING);

    $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_payment->execute([$payment_status, $order_id]);

    if ($update_payment->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment status update failed.']);
    }
}
?>
