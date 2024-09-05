<?php

include '../components/connect.php';

session_start();

if(isset($_POST['purchase'])){
   $product_id = $_POST['product_id'];
   $quantity_purchased = $_POST['quantity'];

   // Retrieve current stock
   $get_stock = $conn->prepare("SELECT quantity FROM `products` WHERE id = ?");
   $get_stock->execute([$product_id]);
   $current_stock = $get_stock->fetchColumn();

   if($current_stock >= $quantity_purchased){
      $new_stock = $current_stock - $quantity_purchased;
      // Update stock level
      $update_stock = $conn->prepare("UPDATE `products` SET quantity = ? WHERE id = ?");
      $update_stock->execute([$new_stock, $product_id]);

      if($update_stock){
         echo 'Product quantity updated!';
      } else {
         echo 'Failed to update product quantity.';
      }
   } else {
      echo 'Insufficient stock for the purchase.';
   }
}

?>
