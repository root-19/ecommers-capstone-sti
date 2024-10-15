<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['id'])){
   $user_id = $_SESSION['id'];
}else{
   $user_id = '';
};


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $review_message = $_POST['reviewMessage'];
   $rating = (int) $_POST['rating'];

   // Handle image upload
   if (isset($_FILES['reviewImage']) && $_FILES['reviewImage']['error'] === 0) {
       $target_dir = "uploads/";
       $image_name = basename($_FILES["reviewImage"]["name"]);
       $target_file = $target_dir . time() . "_" . $image_name;
       $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

       // Validate image file type
       $allowed_types = array("jpg", "jpeg", "png", "gif");
       if (!in_array($imageFileType, $allowed_types)) {
           echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
       } else {
           // Move uploaded image
           if (move_uploaded_file($_FILES["reviewImage"]["tmp_name"], $target_file)) {
               // Insert review into the database
               $stmt = $conn->prepare("INSERT INTO reviews (review_message, rating, image_path) VALUES (?, ?, ?)");
               $stmt->bind_param("sis", $review_message, $rating, $target_file);
               $stmt->execute();
               $stmt->close();
               echo "<script>alert('Review added successfully!');</script>";
           } else {
               echo "<script>alert('There was an error uploading the image.');</script>";
           }
       }
   } else {
       echo "<script>alert('Please upload a valid image.');</script>";
   }
}

// Fetch all reviews from the database
$sql = "SELECT review_message, rating, image_path, created_at FROM reviews ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Review Section</title>
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

   <div class="max-w-2xl mx-auto mt-10">
       <button id="addReviewBtn" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Add Review</button>
       
       <!-- Review Form -->
       <div id="reviewForm" class="hidden bg-white p-4 shadow rounded">
           <form method="POST" enctype="multipart/form-data">
               <div>
                   <label for="reviewMessage" class="block text-gray-700">Review Message:</label>
                   <textarea id="reviewMessage" name="reviewMessage" class="w-full p-2 border border-gray-300 rounded mt-2" rows="4" required></textarea>
               </div>
               <div class="mt-4">
                   <label for="rating" class="block text-gray-700">Rate (1-5):</label>
                   <input id="rating" name="rating" type="number" min="1" max="5" class="w-full p-2 border border-gray-300 rounded mt-2" required>
               </div>
               <div class="mt-4">
                   <label for="reviewImage" class="block text-gray-700">Upload Image:</label>
                   <input id="reviewImage" name="reviewImage" type="file" accept="image/*" class="w-full p-2 border border-gray-300 rounded mt-2" required>
               </div>
               <div class="mt-4 text-right">
                   <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Submit Review</button>
               </div>
           </form>
       </div>

       <!-- Reviews Section -->
       <div id="reviewsSection" class="mt-10">
           <h2 class="text-2xl font-bold mb-4">Customer Reviews</h2>
           <div id="reviewsList">
               <?php if ($result->num_rows > 0): ?>
                   <?php while ($row = $result->fetch_assoc()): ?>
                       <div class="bg-gray-50 p-4 shadow rounded mt-4">
                           <p><strong>Rating:</strong> <?php echo $row['rating']; ?> / 5</p>
                           <img src="<?php echo $row['image_path']; ?>" alt="Review Image" class="w-32 h-32 object-cover mt-2">
                           <p class="mt-2"><?php echo $row['review_message']; ?></p>
                           <small>Reviewed on: <?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></small>
                       </div>
                   <?php endwhile; ?>
               <?php else: ?>
                   <p>No reviews yet. Be the first to add one!</p>
               <?php endif; ?>
           </div>
       </div>
   </div>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
       // Show/Hide review form
       $('#addReviewBtn').on('click', function() {
           $('#reviewForm').toggle();
       });
   </script>




<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>