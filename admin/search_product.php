<?php
include '../components/connect.php';

if(isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $conn->prepare("SELECT id, name, quantity FROM products WHERE name LIKE ? LIMIT 10");
    $stmt->execute(['%' . $query . '%']);

    if($stmt->rowCount() > 0) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div onclick="selectProduct(' . ($row['id']) . ', \'' . ($row['name']) . '\', ' . ($row['quantity']) . ')">';
            echo ($row['name']);
            echo '</div>';
        }
    } else {
        echo '<div>No products found</div>';
    }
}
?>
