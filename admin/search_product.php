<?php
include '../components/connect.php';
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE manufacturer LIKE ? LIMIT 100");
    $stmt->execute(['%' . $query . '%']);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productData = json_encode([
                'id' => $row['id'],
                'name' => $row['name'],
                'manufacturer' => $row['manufacturer'],
                'type' => $row['type'],
                'automobile_type' => $row['automobile_type'],
                'price' => $row['price'],
                'category' => $row['category'],
                'quantity' => $row['quantity']
            ]);

            echo '<tr>';
            echo '<td class="p-2 border border-gray-200">' . htmlspecialchars($row['name']) . '</td>';
            echo '<td class="p-2 border border-gray-200">';
            echo '<input type="text" name="manufacturer[' . $row['id'] . ']" value="' . htmlspecialchars($row['manufacturer']) . '" class="border border-gray-300 p-1 rounded w-full">';
            echo '</td>';
            echo '<td class="p-2 border border-gray-200">' . htmlspecialchars($row['type']) . '</td>';
            echo '<td class="p-2 border border-gray-200">' . htmlspecialchars($row['automobile_type']) . '</td>';
            echo '<td class="p-2 border border-gray-200">' . htmlspecialchars($row['price']) . '</td>';
            echo '<td class="p-2 border border-gray-200">' . htmlspecialchars($row['category']) . '</td>';
            echo '<td class="p-2 border border-gray-200">';
            echo '<input type="number" name="quantity[' . $row['id'] . ']" value="' . htmlspecialchars($row['quantity']) . '" class="border border-gray-300 p-1 rounded w-full">';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="7" class="p-2 text-center">No products found</td></tr>';
    }
}