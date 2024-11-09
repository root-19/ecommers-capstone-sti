<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // Fetch user data
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_user->execute([$delete_id]);
    $user_data = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($user_data) { // Check if user exists
        // Archive user data
        $archive_user = $conn->prepare("INSERT INTO `archived_users` (user_id, name, email) VALUES (?, ?, ?)");
        $archive_user->execute([$user_data['id'], $user_data['name'], $user_data['email']]);

        // Delete related data in the correct order
        // Start with orders
        $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
        $delete_orders->execute([$delete_id]);

        // Then messages
        $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
        $delete_messages->execute([$delete_id]);

        // Then cart
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$delete_id]);

        // Then wishlist
        $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
        $delete_wishlist->execute([$delete_id]);

        // Finally, delete the user from the main users table
        $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
        $delete_user->execute([$delete_id]);

        // Redirect after deletion
        header('location:users_accounts.php');
        exit(); // Ensure no further code is executed after redirect
    } else {
        echo "User not found.";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      table {
         width: 100%;
         border-collapse: collapse;
         margin: 20px 0;
         font-size: 18px;
         text-align: left;
      }

      th, td {
         padding: 12px;
         border-bottom: 1px solid #ddd;
      }

      th {
         background-color: #f4f4f4;
         font-weight: bold;
      }

      tr:nth-child(even) {
         background-color: #f9f9f9;
      }

      tr:hover {
         background-color: #f1f1f1;
      }

      .delete-btn {
         text-decoration: none;
         padding: 5px 10px;
         border-radius: 4px;
         color: #fff;
         background-color: #f44336; /* Red */
      }

      .delete-btn:hover {
         background-color: #e53935;
      }

      .empty {
         text-align: center;
         margin: 20px 0;
         color: #555;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">User Accounts</h1>

   <div class="table-container">
      <table>
         <thead>
            <tr>
               <th>User ID</th>
               <th>Username</th>
               <th>Email</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
            <?php
               $select_accounts = $conn->prepare("SELECT * FROM `users`");
               $select_accounts->execute();
               if($select_accounts->rowCount() > 0){
                  while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
               <td><?= $fetch_accounts['id']; ?></td>
               <td><?= $fetch_accounts['name']; ?></td>
               <td><?= $fetch_accounts['email']; ?></td>
               <td>
                  <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account? The user-related information will also be deleted!')" class="delete-btn">Delete</a>
               </td>
            </tr>
            <?php
                  }
               } else {
                  echo '<tr><td colspan="4" class="empty">No accounts available!</td></tr>';
               }
            ?>
         </tbody>
      </table>
   </div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
