<?php
include '../components/connect.php';

header('Content-Type: application/json');

$total_pendings = 0;
$select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_pendings->execute(['pending']);
while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
    $total_pendings += $fetch_pendings['total_price'];
}

$total_completes = 0;
$select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_completes->execute(['completed']);
while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
    $total_completes += $fetch_completes['total_price'];
}

// Fetch total counts
$number_of_orders = $conn->query("SELECT COUNT(*) FROM `orders`")->fetchColumn();
$number_of_products = $conn->query("SELECT COUNT(*) FROM `products`")->fetchColumn();
$number_of_users = $conn->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
$number_of_admins = $conn->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();
$number_of_messages = $conn->query("SELECT COUNT(*) FROM `messages`")->fetchColumn();

echo json_encode([
    'total_pendings' => $total_pendings,
    'total_completes' => $total_completes,
    'number_of_orders' => $number_of_orders,
    'number_of_products' => $number_of_products,
    'number_of_users' => $number_of_users,
    'number_of_admins' => $number_of_admins,
    'number_of_messages' => $number_of_messages
]);
?>

