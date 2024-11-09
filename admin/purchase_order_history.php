<?php
include '../components/connect.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch purchase order history
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM purchase_orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->execute([$user_id]);
$purchase_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order History</title>
    <link rel="stylesheet" href="path/to/your/tailwind.css"> <!-- Link to Tailwind CSS -->
</head>
<body>

<!-- Header or Navigation Bar -->
<header>
    <h1 class="text-2xl font-bold text-center mt-4">Purchase Order History</h1>
</header>

<!-- Purchase Orders Table -->
<div class="container mx-auto mt-6">
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th class="border px-4 py-2">Order ID</th>
                <th class="border px-4 py-2">Product Name</th>
                <th class="border px-4 py-2">Quantity</th>
                <th class="border px-4 py-2">Order Date</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($purchase_orders) > 0): ?>
                <?php foreach ($purchase_orders as $order): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($order['id']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($order['product_name']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($order['status']); ?></td>
                        <td class="border px-4 py-2">
                            <button onclick="showInvoice(<?php echo $order['id']; ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">View Invoice</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="border px-4 py-2 text-center">No purchase orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Invoice -->
<div id="invoiceModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-90">
    <div class="bg-white p-4 rounded">
        <h2 class="text-xl font-bold mb-2">Invoice Details</h2>
        <div id="invoiceDetails"></div>
        <button onclick="closeInvoice()" class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Close</button>
    </div>
</div>

<script>
// Function to show invoice details
function showInvoice(orderId) {
    // Here you would fetch the invoice details for the orderId
    // For simplicity, we're just displaying a placeholder message
    document.getElementById('invoiceDetails').innerText = 'Invoice details for Order ID: ' + orderId;
    document.getElementById('invoiceModal').classList.remove('hidden');
}

// Function to close the invoice modal
function closeInvoice() {
    document.getElementById('invoiceModal').classList.add('hidden');
}
</script>

</body>
</html>
