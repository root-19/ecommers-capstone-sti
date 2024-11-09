<?php
include '../components/connect.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? LIMIT 10");
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
                'selling_price' => $row['selling_price'],
                'details' => $row['details'],
                'category' => $row['category'],
                'quantity' => $row['quantity']
            ]);

            echo '<div onclick=\'selectProduct(' . $productData . ')\'>';
            echo htmlspecialchars($row['name']);
            echo '</div>';
        }
    } else {
        echo '<div>No products found</div>';
    }
}
?>



