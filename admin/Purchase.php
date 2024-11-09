<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['order_purchase'])) {
    // Loop through all submitted products and insert them into the purchase table
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $manufacturer = $_POST['manufacturer'][$product_id];
        $productQuery = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $productQuery->execute([$product_id]);
        $product = $productQuery->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $name = $product['name'];
            $type = $product['type'];
            $automobile_type = $product['automobile_type'];
            $category = $product['category'];
            $price = $product['price'];
            $purchase_date = date('Y-m-d');

            // Generate a random code number
            $code_number = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

            // Insert the product into the purchase table
            $insert_purchase = $conn->prepare("INSERT INTO `purchase` (product_id, name, manufacturer, type, automobile_type, category, price, quantity, purchase_date, code_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_purchase->execute([$product_id, $name, $manufacturer, $type, $automobile_type, $category, $price, $quantity, $purchase_date, $code_number]);

            if ($insert_purchase) {
                // $message[] = 'Purchase order added for  !';
            }
        } else {
            $message[] = 'Product with ID ' . $product_id . ' not found!';
        }
    }
}


// Handle receiving an order and moving to receive_order table
if (isset($_POST['receive_order'])) {
    $product_id = $_POST['product_id'];

    // Get the order details from the purchase table
    $purchaseQuery = $conn->prepare("SELECT * FROM `purchase` WHERE product_id = ?");
    $purchaseQuery->execute([$product_id]);
    $purchase = $purchaseQuery->fetch(PDO::FETCH_ASSOC);

    if ($purchase) {
        // Define code number for receiving the order
        $code_number = $purchase['code_number'];  // Use the code number from the purchase table

        // Insert into receive_order table
        $insert_receive = $conn->prepare("INSERT INTO `receive_order` (product_id, name, manufacturer, type, automobile_type, category, price, quantity, code_number, receive_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $receive_date = date('Y-m-d');
        $insert_receive->execute([
            $purchase['product_id'], 
            $purchase['name'], 
            $purchase['manufacturer'], 
            $purchase['type'], 
            $purchase['automobile_type'], 
            $purchase['category'], 
            $purchase['price'], 
            $purchase['quantity'], 
            $code_number, 
            $receive_date
        ]);

        if ($insert_receive) {
            // Update the product's quantity in the products table
            $update_product = $conn->prepare("UPDATE `products` SET quantity = quantity + ? WHERE id = ?");
            $update_product->execute([$purchase['quantity'], $product_id]);

            // Delete from the purchase table
            $delete_purchase = $conn->prepare("DELETE FROM `purchase` WHERE product_id = ?");
            $delete_purchase->execute([$product_id]);

            $message[] = 'Order received and product quantity updated successfully!';
        } else {
            $message[] = 'Failed to receive order.';
        }
    } else {
        $message[] = 'Product not found in purchase table.';
    }
}

// Fetching purchase history and received orders
$receivedOrders = $conn->query("SELECT * FROM `receive_order` ORDER BY receive_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$purchases = $conn->query("SELECT * FROM `purchase`")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();

// Fetch all the data as an associative array
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include '../components/admin_header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<br>

<!-- Button to open the Restock Pop-up -->
<div class="flex justify-start ml-4">
    <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:ring-2 focus:ring-red-400 transition-all text-3xl" id="restockProductBtn">
        Order Product
    </button>
</div>

        <!-- Product Table Layout with Scrollable Body -->
        <form method="POST"> 
        <div id="restockForm" class="fixed inset-0 flex mt-40 items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-7xl max-h-[80vh] overflow-hidden">
        <button class="close text-gray-400 float-right text-xl font-semibold">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-center">Restock Product</h2>

            <div class="overflow-x-auto mb-4" style="max-height: 60vh; overflow-y: auto;">
                <table class="min-w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-sm font-semibold text-gray-700">
                            <th class="p-2 border border-gray-200">Name</th>
                            <th class="p-2 border border-gray-200">Manufacturer</th>
                            <th class="p-2 border border-gray-200">Type</th>
                            <th class="p-2 border border-gray-200">Automobile Type</th>
                            <th class="p-2 border border-gray-200">Price</th>
                            <th class="p-2 border border-gray-200">Category</th>
                            <th class="p-2 border border-gray-200">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="p-2 border border-gray-200"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td class="p-2 border border-gray-200">
                                    <input type="text" name="manufacturer[<?php echo $product['id']; ?>]" value="<?php echo htmlspecialchars($product['manufacturer']); ?>" class="border border-gray-300 p-1 rounded w-full">
                                </td>
                                <td class="p-2 border border-gray-200"><?php echo htmlspecialchars($product['type']); ?></td>
                                <td class="p-2 border border-gray-200"><?php echo htmlspecialchars($product['automobile_type']); ?></td>
                                <td class="p-2 border border-gray-200"><?php echo htmlspecialchars($product['price']); ?></td>
                                <td class="p-2 border border-gray-200"><?php echo htmlspecialchars($product['category']); ?></td>
                                <td class="p-2 border border-gray-200">
                                    <input type="number" name="quantity[<?php echo $product['id']; ?>]" value="<?php echo htmlspecialchars($product['quantity']); ?>" class="border border-gray-300 p-1 rounded w-full">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Order Purchase Button (Fixed to the bottom of the modal) -->
            <button type="submit" name="order_purchase" class="w-full mt-4 bg-red-500 text-white py-2 rounded hover:bg-red-600 transition-all">
                Order Purchase
            </button>
        </form> <!-- End form tag here -->
    </div>
</div> 

<!-- Purchases Table -->
<div class="mt-8 max-w-7xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">Purchases</h2>
    <table class="w-full text-left bg-white rounded-lg overflow-hidden shadow">
        <thead class="bg-gray-200 text-gray-600">
            <tr>
                <?php $columns = ['Code Number', 'Product Name', 'Price', 'Quantity', 'Total Price', 'Action'];
                foreach ($columns as $column): ?>
                    <th class="py-3 px-4 text-center font-medium"><?= $column ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $purchase): ?>
                <tr class="hover:bg-gray-50 border-b">
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['code_number']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($purchase['name']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['price']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['quantity']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($purchase['price'] * $purchase['quantity']) ?></td>
                    <td class="py-2 px-4 text-center">
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $purchase['product_id'] ?>">
                            <button type="submit" name="receive_order" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition-all">
                                Receive
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Purchase Order History Table -->
<div class="mt-12 max-w-7xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">Purchase Order History</h2>
    <table class="w-full text-left bg-white rounded-lg overflow-hidden shadow">
        <thead class="bg-gray-200 text-gray-600">
            <tr>
                <?php $historyColumns = ['Code Number', 'Product Name', 'Manufacturer', 'Type', 'Automobile Type', 'Category', 'Price', 'Quantity', 'Total Price', 'Receive Date'];
                foreach ($historyColumns as $column): ?>
                    <th class="py-3 px-4 text-center font-medium"><?= $column ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($receivedOrders as $order): ?>
                <tr class="hover:bg-gray-50 border-b">
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['code_number']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['name']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['manufacturer']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['type']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['automobile_type']) ?></td>
                    <td class="py-2 px-4"><?= htmlspecialchars($order['category']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['price']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['quantity']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['price'] * $order['quantity']) ?></td>
                    <td class="py-2 px-4 text-center"><?= htmlspecialchars($order['receive_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<!-- CSS for the modal pop-up -->
<style>
    .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close:hover { color: black; }
    #searchResults div { cursor: pointer; padding: 5px; }
    #searchResults div:hover { background-color: #f1f1f1; }
</style>

<!-- JavaScript for modal functionality and search -->
<script>
    const modal = document.getElementById('restockForm');
    const btn = document.getElementById('restockProductBtn');
    const span = document.getElementsByClassName('close')[0];

    btn.onclick = function() {
        modal.classList.remove('hidden');
    }

    span.onclick = function() {
        modal.classList.add('hidden');
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }

    function searchProduct(query) {
        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = '';
            return;
        }

        fetch(`search_product.php?query=${query}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('searchResults').innerHTML = data;
            });
    }

    function selectProduct(product) {
        document.getElementById('product_id').value = product.id;
        document.getElementById('name').value = product.name;
        document.getElementById('manufacturer').value = product.manufacturer;
        document.getElementById('type').value = product.type;
        document.getElementById('automobile_type').value = product.automobile_type;
        document.getElementById('category').value = product.category;
        document.getElementById('price').value = product.price;
        document.getElementById('searchResults').innerHTML = '';
    }
</script>

