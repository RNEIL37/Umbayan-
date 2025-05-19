<?php
@include 'config.php';
if(isset($_POST['update_update_btn'])){
   $update_value = $_POST['update_quantity'];
   $update_id = $_POST['update_quantity_id'];
   $update_quantity_query = mysqli_query($conn, "UPDATE `user_form` SET quantity = '$update_value' WHERE id = '$update_id'");
   if($update_quantity_query){
      header('location:cart.php');
   };
}
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_query = mysqli_query($conn, "DELETE FROM `user_form` WHERE id = $delete_id ") or die('query failed');
    if($delete_query){
       header('location:user_display.php');
       $message[] = 'product has been deleted';
    }else{
       header('location:user_display.php');
       $message[] = 'product could not be deleted';
    };
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>
   
   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style1.css">

   <!-- Google Fonts Link -->
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Roboto', sans-serif; background-color: #f5f5f5;">

<!-- Header Section -->
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
<!-- Main Content -->
<section class="shopping-cart">
   <h1 class="heading">List of Users</h1>

   <!-- Create Account Button -->
   <div style="text-align: right; margin-bottom: 20px;">
      <a href="register_form1.php" style="background-color: #27ae60; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: 600;">
         <i class="fas fa-user-plus"></i> Create Account for delivery
      </a>
   </div>

   <table class="user-table">
      <thead>
         <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Actions</th>
         </tr>
      </thead>
      <tbody >

         <?php 
            $select_cart = mysqli_query($conn, "SELECT * FROM `user_form`");
            if(mysqli_num_rows($select_cart) > 0){
               while($fetch_cart = mysqli_fetch_assoc($select_cart)){
         ?>
         <tr>
            <td><?php echo $fetch_cart['name']; ?></td>
            <td><?php echo $fetch_cart['email']; ?></td>
            <td><?php echo $fetch_cart['user_type']; ?></td>
          <td>
   <a href="user_display.php?delete=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">
      <i class="fas fa-trash"></i> Delete
   </a>
</td>
         </tr>
         <?php 
            };
         };
         ?>
      </tbody>
   </table>
</section>

<!-- Custom CSS -->
<style>
   .edit-btn {
   display: inline-block;
   background-color: #f39c12;
   color: white;
   padding: 8px 12px;
   border-radius: 5px;
   text-decoration: none;
   font-weight: 600;
   cursor: pointer;
   margin-right: 8px;
   transition: background-color 0.3s ease-in-out;
}

.edit-btn:hover {
   background-color: #d68910;
}

   /* Header and Navigation Styles */
   header {
      background-color: #34495e;
   }
   
   header nav a {
      transition: color 0.3s ease-in-out;
   }

   header nav a:hover {
      color: #27ae60;
   }

   /* Main Section Styles */
   .shopping-cart {
      padding: 40px 20px;
      max-width: 1200px;
      margin: 40px auto;
      background-color: white;
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
   }

   .heading {
      font-size: 28px;
      font-weight: 700;
      color: #2c3e50;
      text-align: center;
      margin-bottom: 30px;
   }

   /* Table Styles */
   .user-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0 auto;
   }

   .user-table th,
   .user-table td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
   }

   .user-table th {
      background-color: #2980b9;
      color: white;
   }

   .user-table tr:hover {
      background-color: #ecf0f1;
   }

   /* Button Styles */
   .delete-btn {
      display: inline-block;
      background-color: #e74c3c;
      color: white;
      padding: 8px 12px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
   }

   .delete-btn:hover {
      background-color: #c0392b;
   }

   /* Footer Styles */
   footer {
      margin-top: 40px;
      font-size: 14px;
   }
</style>

</body>
</html>
