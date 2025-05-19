<?php
@include 'config.php';

// Add Product
if(isset($_POST['add_product'])){
   $ps_name = mysqli_real_escape_string($conn, $_POST['ps_name']);
   $p_description = mysqli_real_escape_string($conn, $_POST['p_description']);
   $ps_price = mysqli_real_escape_string($conn, $_POST['ps_price']);
   $ps_category = mysqli_real_escape_string($conn, $_POST['ps_category']);
   $ps_image = $_FILES['ps_image']['name'];
   $ps_image_tmp_name = $_FILES['ps_image']['tmp_name'];
   $ps_image_folder = 'uploaded_img/'.$ps_image;

   $insert_query = mysqli_query($conn, "INSERT INTO `catering` (name, category, description, price, image) VALUES ('$ps_name', '$ps_category', '$p_description', '$ps_price', '$ps_image')") or die('Insert Query Failed');

   if($insert_query){
      move_uploaded_file($ps_image_tmp_name, $ps_image_folder);
   }
}

// Delete Product
if(isset($_GET['delete'])){
   $delete_id = (int)$_GET['delete'];
   $delete_query = mysqli_query($conn, "DELETE FROM `catering` WHERE id = $delete_id") or die('Delete Query Failed');
   header('location:catering.php');
   exit;
}

// Update Product
if(isset($_POST['update_product'])){
   $update_p_id = mysqli_real_escape_string($conn, $_POST['update_p_id']);
   $update_p_name = mysqli_real_escape_string($conn, $_POST['update_p_name']);
   $update_p_category = mysqli_real_escape_string($conn, $_POST['update_p_category']);
   $update_p_description = mysqli_real_escape_string($conn, $_POST['update_p_description']);
   $update_p_price = mysqli_real_escape_string($conn, $_POST['update_p_price']);

   if(isset($_FILES['update_p_image']) && $_FILES['update_p_image']['name'] != ""){
      $update_p_image = $_FILES['update_p_image']['name'];
      $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
      $update_p_image_folder = 'uploaded_img/'.$update_p_image;

      $update_query = mysqli_query($conn, "UPDATE `catering` SET name='$update_p_name', category='$update_p_category', price='$update_p_price', description='$update_p_description', image='$update_p_image' WHERE id='$update_p_id'") or die('Update Query Failed');

      if($update_query){
         move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
         header('location:catering.php');
         exit;
      }
   }
}

// Search Functionality
$search_query = "";
if(isset($_POST['search'])){
   $search = mysqli_real_escape_string($conn, $_POST['search_term']);
   $search_query = "WHERE name LIKE '%$search%' OR description LIKE '%$search%' OR category LIKE '%$search%'";
}

$select_products = mysqli_query($conn, "SELECT * FROM `catering` $search_query");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Catering Admin Panel</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <style>
      .edit-form-container {
         display: none;
      }
   </style>
</head>
<body style="margin:0; font-family:Arial, sans-serif; background:#f4f4f4; color:#333;">

<!-- Header -->
<header style="background:#2c3e50; padding:20px 40px; color:white;">
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div style="font-size:24px; font-weight:bold;">FOOD ORDERING AND CATERING SYSTEM</div>
      <nav style="display:flex; gap:15px;">
         <a href="admin.php" style="color:#ecf0f1; text-decoration:none;">Menu</a>
         <a href="catering.php" style="color:#ecf0f1; text-decoration:none;">Catering menu</a>
         <a href="order_user.php" style="color:#ecf0f1; text-decoration:none;">
            Orders <span style="background:red; color:white; padding:2px 6px; border-radius:10px;"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `orders`")); ?></span>
         </a>
         <a href="user_display.php" style="color:#ecf0f1; text-decoration:none;">
            Users <span style="background:red; color:white; padding:2px 6px; border-radius:10px;"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `user_form`")); ?></span>
         </a>
         <a href="logout.php" style="background:#e74c3c; color:white; padding:6px 12px; border-radius:5px; text-decoration:none;">Logout</a>
      </nav>
   </div>
</header>

<!-- Form -->
<section style="max-width:600px; margin:40px auto; background:white; padding:20px; border-radius:10px; box-shadow:0 8px 16px rgba(0,0,0,0.1);">
   <form action="" method="post" enctype="multipart/form-data">
      <h2 style="text-align:center; color:#2c3e50;">Add Catering Product</h2>
      <input type="text" name="ps_name" placeholder="Product Name" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;">
      <input type="text" name="p_description" placeholder="Product Description" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;">
      <select name="ps_category" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;">
         <option value="" disabled selected>Select Catering Type</option>
         <option value="Wedding">Wedding</option>
         <option value="Birthday">Birthday</option>
         <option value="Buffet">Buffet</option>
         <option value="Social Event Catering">Social Event Catering</option>
      </select>
      <input type="number" name="ps_price" min="0" placeholder="Product Price" required style="width:100%; padding:10px; margin:10px 0; border-radius:5px; border:1px solid #ccc;">
      <input type="file" name="ps_image" accept="image/*" required style="margin:10px 0;">
      <input type="submit" name="add_product" value="Add Product" style="width:100%; background:#27ae60; color:white; padding:12px; border:none; border-radius:5px; cursor:pointer;">
   </form>
</section>

<!-- Display Table -->
<section style="padding:20px; max-width:1100px; margin:auto;">
   <table style="width:100%; border-collapse:collapse; background:white; box-shadow:0 4px 12px rgba(0,0,0,0.05); border-radius:10px; overflow:hidden;">
      <thead>
         <!-- Search Bar and Button -->
          <?php
$search_query = "";
if(isset($_POST['search'])){
   $search = mysqli_real_escape_string($conn, $_POST['search_term']);
   $search_query = "WHERE name LIKE '%$search%' OR description LIKE '%$search%' OR category LIKE '%$search%'";
}

$select_products = mysqli_query($conn, "SELECT * FROM `catering` $search_query");
?>

      <!-- Display Section -->
<section style="padding:20px; max-width:1100px; margin:auto;">

   <!-- Search Form -->
  <!-- Display Table -->
<section style="padding:20px; max-width:1100px; margin:auto;">

   <!-- Centered Search Form -->
   <div style="text-align:center; margin-bottom: 30px;">
      <form method="post" style="display:inline-flex; gap:10px; align-items:center;">
         <input type="text" name="search_term" placeholder="Search products..." 
            style="padding: 10px; width: 250px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px;">
         <button type="submit" name="search" 
            style="padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer;">
            Search
         </button>
      </form>
   </div>
</section>


   <!-- Product Table -->
   <table style="width:100%; border-collapse:collapse; background:white; box-shadow:0 4px 12px rgba(0,0,0,0.05); border-radius:10px; overflow:hidden;">
      <thead>
         <tr style="background:#34495e; color:white;">
            <th style="padding:12px;">Image</th>
            <th style="padding:12px;">Name</th>
            <th style="padding:12px;">Category</th>
            <th style="padding:12px;">Description</th>
            <th style="padding:12px;">Price</th>
            <th style="padding:12px;">Action</th>
         </tr>
      </thead>
      <tbody>
         <?php
         if(mysqli_num_rows($select_products) > 0){
            while($row = mysqli_fetch_assoc($select_products)){
         ?>
         <tr style="border-bottom:1px solid #eee;">
            <td style="padding:10px;"><img src="uploaded_img/<?php echo $row['image']; ?>" height="80" style="border-radius:8px;"></td>
            <td style="padding:10px;"><?php echo $row['name']; ?></td>
            <td style="padding:10px;"><?php echo $row['category']; ?></td>
            <td style="padding:10px;"><?php echo $row['description']; ?></td>
            <td style="padding:10px;">₱<?php echo number_format($row['price'], 2); ?></td>
            <td style="padding:10px;">
               <a href="catering.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');" style="background:#e74c3c; color:white; padding:6px 12px; border-radius:4px; text-decoration:none; margin-right:5px;"><i class="fas fa-trash"></i> Delete</a>
               <a href="catering.php?edit=<?php echo $row['id']; ?>" style="background:#2980b9; color:white; padding:6px 12px; border-radius:4px; text-decoration:none;"><i class="fas fa-edit"></i> Edit</a>
            </td>
         </tr>
         <?php }} else {
            echo "<tr><td colspan='6' style='text-align:center; padding:20px; color:#999;'>No products found.</td></tr>";
         } ?>
      </tbody>
   </table>
</section>

</section>

<!-- Edit Form -->
<?php
if(isset($_GET['edit'])){
   $edit_id = $_GET['edit'];
   $edit_query = mysqli_query($conn, "SELECT * FROM `catering` WHERE id = $edit_id");
   if(mysqli_num_rows($edit_query) > 0){
      while($fetch_edit = mysqli_fetch_assoc($edit_query)){
?>
<section class="edit-form-container" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); display:flex; justify-content:center; align-items:center; z-index:9999;">
   <form action="" method="post" enctype="multipart/form-data" style="background:white; padding:30px; border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,0.3); width:100%; max-width:500px; position:relative;">
      <a href="catering.php" onclick="document.querySelector('.edit-form-container').style.display='none'" style="position:absolute; top:10px; right:15px; text-decoration:none; font-size:20px; color:#333;">&times;</a>
      <h2 style="text-align:center; color:#2c3e50; margin-bottom:20px;">Edit Product</h2>
      <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="120" style="display:block; margin:10px auto 20px; border-radius:10px;">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
      <input type="text" name="update_p_name" value="<?php echo $fetch_edit['name']; ?>" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px;">
      <input type="text" name="update_p_description" value="<?php echo $fetch_edit['description']; ?>" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px;">
      <select name="update_p_category" required style="width:100%; padding:10px; margin-bottom:10px; border-radius:5px; border:1px solid #ccc;">
         <option value="Wedding" <?php if($fetch_edit['category'] == 'Wedding') echo 'selected'; ?>>Wedding</option>
         <option value="Birthday" <?php if($fetch_edit['category'] == 'Birthday') echo 'selected'; ?>>Birthday</option>
         <option value="Buffet" <?php if($fetch_edit['category'] == 'Buffet') echo 'selected'; ?>>Buffet</option>
         <option value="Social Event Catering" <?php if($fetch_edit['category'] == 'Social Event Catering') echo 'selected'; ?>>Social Event Catering</option>
      </select>
      <input type="number" name="update_p_price" value="<?php echo $fetch_edit['price']; ?>" required style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:5px;">
      <input type="file" name="update_p_image" accept="image/*" required style="margin-bottom:20px;">
      <input type="submit" name="update_product" value="Update Product" style="background:#27ae60; color:white; padding:12px; width:100%; border:none; border-radius:5px; cursor:pointer;">
      <br><br>
      <button type="button" onclick="document.querySelector('.edit-form-container').style.display='none'" style="width:100%; background:#e74c3c; color:white; padding:12px; border:none; border-radius:5px; cursor:pointer; margin-bottom:10px;">Cancel</button>
   </form>
</section>

<?php
      }
   }
}
?>
</body>
</html>
