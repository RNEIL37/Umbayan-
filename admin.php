<?php

@include 'config.php';

$categories = ['Appetizer', 'Main dish', 'Dessert', 'Beverages','fast food','softdrinks']; // You can customize this

if(isset($_POST['add_product'])){
   $p_name = $_POST['p_name'];
   $p_description = $_POST['p_description'];
   $p_price = $_POST['p_price'];
   $p_category = $_POST['p_category'];
   $p_image = $_FILES['p_image']['name'];
   $p_image_tmp_name = $_FILES['p_image']['tmp_name'];
   $p_image_folder = 'uploaded_img/'.$p_image;

   $insert_query = mysqli_query($conn, "INSERT INTO `products` (name, description, price, category, image) VALUES ('$p_name', '$p_description', '$p_price', '$p_category', '$p_image')") or die('query failed');

   if($insert_query){
      move_uploaded_file($p_image_tmp_name, $p_image_folder);
   }
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_query = mysqli_query($conn, "DELETE FROM `products` WHERE id = $delete_id") or die('query failed');
   header('location:admin.php');
};

if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $update_p_name = $_POST['update_p_name'];
   $update_p_description = $_POST['update_p_description'];
   $update_p_price = $_POST['update_p_price'];
   $update_p_category = $_POST['update_p_category'];
   $update_p_image = $_FILES['update_p_image']['name'];
   $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
   $update_p_image_folder = 'uploaded_img/'.$update_p_image;

   // If no new image uploaded, keep the old image
   if(empty($update_p_image)){
       // Fetch old image name
       $img_res = mysqli_query($conn, "SELECT image FROM products WHERE id = '$update_p_id'") or die('query failed');
       $img_row = mysqli_fetch_assoc($img_res);
       $update_p_image = $img_row['image'];
   } else {
       move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
   }

   $update_query = mysqli_query($conn, "UPDATE `products` SET name = '$update_p_name', description = '$update_p_description', price = '$update_p_price', category = '$update_p_category', image = '$update_p_image' WHERE id = '$update_p_id'") or die('query failed');

   header('location:admin.php');
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="margin:0; font-family:'Segoe UI', sans-serif; background:#f5f5f5; color:#333;">
<header style="background:#2c3e50; color:#fff; padding: 20px 40px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      
      <div style="font-size:24px; font-weight:bold;">FOOD ORDERING AND CATERING SYSTEM</div>
      
      <nav style="display:flex; gap:20px; align-items:center; flex-wrap:wrap;">
         <a href="admin.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">Menu</a>
         <a href="catering.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">Catering Menu</a>

         <a href="order_user.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">
            Orders
            <span style="background:red; color:white; padding:2px 8px; border-radius:12px; font-size:0.85em;">
               <?php
               $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
               echo mysqli_num_rows($select_orders);
               ?>
            </span>
         </a>

         <a href="user_display.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">
            Users
            <span style="background:red; color:white; padding:2px 8px; border-radius:12px; font-size:0.85em;">
               <?php
               $select_users = mysqli_query($conn, "SELECT * FROM `user_form`") or die('query failed');
               echo mysqli_num_rows($select_users);
               ?>
            </span>
         </a>

         <a href="logout.php" style="background:#e74c3c; padding:8px 16px; color:white; border-radius:5px; text-decoration:none; font-weight:500;">
            Logout
         </a>
      </nav>
   </div>
</header>
<style>
   .add-product-form {
      background: #fff;
      padding: 20px;
      margin: 30px auto;
      max-width: 500px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      border-radius: 10px;
   }

   .add-product-form h3 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
   }

   .add-product-form .box {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
   }

   .add-product-form .btn {
      background: #27ae60;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 6px;
      width: 100%;
      font-size: 16px;
   }

   .display-product-table {
      padding: 20px;
      max-width: 1200px;
      margin: auto;
   }

   .display-product-table table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 6px 12px rgba(0,0,0,0.05);
      border-radius: 10px;
      overflow: hidden;
   }

   .display-product-table th, .display-product-table td {
      padding: 12px 15px;
      text-align: left;
   }

   .display-product-table th {
      background: #34495e;
      color: white;
   }

   .display-product-table tr:nth-child(even) {
      background: #f2f2f2;
   }

   .display-product-table tr:hover {
      background: #eafaf1;
   }

   .option-btn, .delete-btn {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
      font-weight: bold;
   }

   .option-btn {
      background: #2980b9;
      color: white;
   }

   .delete-btn {
      background: #e74c3c;
      color: white;
   }

   .empty {
      text-align: center;
      padding: 20px;
      font-size: 18px;
      color: #999;
   }
</style>
<body style="margin:0; font-family:'Segoe UI', sans-serif; background: url('ricoslechon_large.jpg') no-repeat center center fixed; background-size: cover; color:#333;">
<section>
   <form action="" method="post" class="add-product-form" enctype="multipart/form-data">
  <h3>Add a New Product</h3>
  <input type="text" name="p_name" placeholder="Enter the product name" class="box" required>
  <input type="text" name="p_description" placeholder="Enter the product description" class="box" required>

  <select name="p_category" class="box" required>
    <option value="" disabled selected>Select Category</option>
    <?php foreach($categories as $cat): ?>
       <option value="<?= $cat ?>"><?= $cat ?></option>
    <?php endforeach; ?>
  </select>

  <input type="number" name="p_price" min="0" placeholder="Enter the product price" class="box" required>
  <input type="file" name="p_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
  <input type="submit" value="Add Product" name="add_product" class="btn">
</form>


</section>
<!-- Search Bar and Button -->
<section class="display-product-table">
   <div style="margin-bottom: 20px; display: flex; justify-content: center; align-items: center; gap: 10px;">
      <input type="text" id="searchInput" placeholder="Search products..." 
         style="padding: 10px; width: 250px; max-width: 100%; border: 1px solid #ccc; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); font-size: 16px;">
      <button onclick="searchProduct()" style="padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer;">
         Search
      </button>
   </div>
<table id="productTable">
  <thead>
    <tr>
      <th>Product Image</th>
      <th>Name</th>
      <th>Description</th>
      <th>Category</th>  <!-- New Category Header -->
      <th>Price</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $select_products = mysqli_query($conn, "SELECT * FROM `products`");
    if(mysqli_num_rows($select_products) > 0){
       while($row = mysqli_fetch_assoc($select_products)){
    ?>
    <tr>
      <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="80" style="border-radius:8px;" alt=""></td>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo $row['description']; ?></td>
      <td><?php echo $row['category']; ?></td> <!-- Show category -->
      <td>₱<?php echo number_format($row['price'], 2); ?></td>
      <td>
         <a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">
            <i class="fas fa-trash"></i> Delete
         </a>
         <a href="admin.php?edit=<?php echo $row['id']; ?>" class="option-btn">
            <i class="fas fa-edit"></i> Update
         </a>
      </td>
    </tr>
    <?php
       }
    } else {
       echo '<tr><td colspan="6" class="empty">No products added yet.</td></tr>';
    }
    ?>
  </tbody>
</table>


</section>

<script>
// JavaScript function to search products
function searchProduct() {
   const input = document.getElementById('searchInput').value.toLowerCase();
   const table = document.getElementById('productTable');
   const rows = table.getElementsByTagName('tr');

   for (let i = 1; i < rows.length; i++) {
      const cells = rows[i].getElementsByTagName('td');
      let matchFound = false;

      for (let j = 0; j < cells.length; j++) {
         if (cells[j]) {
            const text = cells[j].textContent || cells[j].innerText;
            if (text.toLowerCase().indexOf(input) > -1) {
               matchFound = true;
               break;
            }
         }
      }

      rows[i].style.display = matchFound ? '' : 'none';
   }
}
</script>


<section class="edit-form-container" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">

   <div id="edit-form" style="background:#fff; padding:30px; border-radius:10px; width:90%; max-width:500px; position:relative; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
      <span onclick="document.querySelector('.edit-form-container').style.display='none'" style="position:absolute; top:10px; right:15px; font-size:20px; cursor:pointer; color:#c0392b;">&times;</span>

      <?php
      if(isset($_GET['edit'])){
         $edit_id = $_GET['edit'];
         $edit_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = $edit_id");
         if(mysqli_num_rows($edit_query) > 0){
            while($fetch_edit = mysqli_fetch_assoc($edit_query)){
      ?>
     <form action="" method="post" enctype="multipart/form-data">
  <img src="uploaded_img/<?php echo $fetch_edit['image']; ?>" height="150" style="border-radius:8px; margin-bottom:15px;" alt="">
  <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
  <input type="text" class="box" required name="update_p_name" value="<?php echo $fetch_edit['name']; ?>" placeholder="Product Name" style="width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:6px;">
  <input type="text" class="box" required name="update_p_description" value="<?php echo $fetch_edit['description']; ?>" placeholder="Description" style="width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:6px;">

  <select name="update_p_category" class="box" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:6px;">
    <?php foreach($categories as $cat): ?>
      <option value="<?= $cat ?>" <?php if($cat == $fetch_edit['category']) echo 'selected'; ?>><?= $cat ?></option>
    <?php endforeach; ?>
  </select>

  <input type="number" min="0" class="box" required name="update_p_price" value="<?php echo $fetch_edit['price']; ?>" placeholder="Price" style="width:100%; padding:10px; margin:10px 0; border:1px solid #ccc; border-radius:6px;">
  <input type="file" class="box" name="update_p_image" accept="image/png, image/jpg, image/jpeg" style="margin:10px 0;">
  <input type="submit" value="Update Product" name="update_product" class="btn" style="background:#27ae60; color:#fff; padding:10px 20px; border:none; border-radius:6px; cursor:pointer; width:100%; margin-top:10px;">
  <button type="button" onclick="document.querySelector('.edit-form-container').style.display='none'" class="option-btn" style="background:#95a5a6; color:#fff; width:100%; margin-top:10px; border:none; padding:10px; border-radius:6px; cursor:pointer;">Cancel</button>
</form>

      <?php
            };
         };
         echo "<script>document.querySelector('.edit-form-container').style.display = 'flex';</script>";
      };
      ?>
   </div>

</section>


</div>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>