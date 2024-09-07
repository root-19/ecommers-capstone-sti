<?php
include '../components/connect.php';

header('Content-Type: application/json');

$total_pendings = 0;
$select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_pendings->execute(['pending']);
if ($select_pendings->rowCount() > 0) {
    while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
        $total_pendings += $fetch_pendings['total_price'];
    }
}

$total_completes = 0;
$select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
$select_completes->execute(['completed']);
if ($select_completes->rowCount() > 0) {
    while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
        $total_completes += $fetch_completes['total_price'];
    }
}

$select_orders = $conn->prepare("SELECT * FROM `orders`");
$select_orders->execute();
$number_of_orders = $select_orders->rowCount();

$select_products = $conn->prepare("SELECT * FROM `products`");
$select_products->execute();
$number_of_products = $select_products->rowCount();

$select_users = $conn->prepare("SELECT * FROM `users`");
$select_users->execute();
$number_of_users = $select_users->rowCount();

$select_admins = $conn->prepare("SELECT * FROM `admins`");
$select_admins->execute();
$number_of_admins = $select_admins->rowCount();

$select_messages = $conn->prepare("SELECT * FROM `messages`");
$select_messages->execute();
$number_of_messages = $select_messages->rowCount();

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
