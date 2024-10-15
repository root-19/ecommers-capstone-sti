<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->execute([$delete_id]);
    header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body class="bg-gray-100">

<?php include '../components/admin_header.php'; ?>

<section class="contacts py-10 px-4">
    <h1 class="heading text-3xl font-bold text-center mb-8">Messages</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-4 border-b border-gray-200 text-left">User ID</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left">Name</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left">Email</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left">Number</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left">Message</th>
                    <th class="py-3 px-4 border-b border-gray-200 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php
                $select_messages = $conn->prepare("SELECT * FROM `messages`");
                $select_messages->execute();
                if ($select_messages->rowCount() > 0) {
                    while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr class="hover:bg-gray-100">
                    <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($fetch_message['user_id']); ?></td>
                    <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($fetch_message['name']); ?></td>
                    <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($fetch_message['email']); ?></td>
                    <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($fetch_message['number']); ?></td>
                    <td class="py-3 px-4 border-b border-gray-200"><?= htmlspecialchars($fetch_message['message']); ?></td>
                    <td class="py-3 px-4 border-b border-gray-200">
                        <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');" class="text-red-500 hover:text-red-700 font-semibold">Delete</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6" class="py-3 px-4 border-b border-gray-200 text-center text-gray-500">You have no messages.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>










<script src="../js/admin_script.js"></script>
   
</body>
</html>