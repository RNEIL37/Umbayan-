<?php
@include 'config.php';

// Update order quantity
if (isset($_POST['update_update_btn'])) {
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];
    $update_quantity_query = mysqli_query($conn, "UPDATE `orders` SET quantity = '$update_value' WHERE id = '$update_id'");
    if ($update_quantity_query) {
        header('location:cart.php');
    }
}

// Update food order status
if (isset($_POST['update_status_btn']) && isset($_POST['type']) && $_POST['type'] === 'orders') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $allowed_statuses = ['delivered', 'on the way', 'Cancelled'];

    if (in_array($new_status, $allowed_statuses)) {
        mysqli_query($conn, "UPDATE `orders` SET status = '$new_status' WHERE id = '$order_id'");
    }
    header('location:order_user.php');
    exit;
}

// Update catering order status
if (isset($_POST['update_status_btn']) && isset($_POST['type']) && $_POST['type'] === 'order_cater') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $allowed_statuses = ['delivered', 'on the way', 'Cancelled'];

    if (in_array($new_status, $allowed_statuses)) {
        mysqli_query($conn, "UPDATE `order_cater` SET status = '$new_status' WHERE id = '$order_id'");
    }
    header('location:order_user.php');
    exit;
}

// Delete all orders
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `orders`");
    header('location:cart.php');
}

$orders_query = "SELECT * FROM `orders`";
$cater_query = "SELECT * FROM `order_cater`";

// Calculate total price for food orders
$food_total_result = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM orders" . (isset($search_value) ? " WHERE name LIKE '%$search_value%' OR purok LIKE '%$search_value%' OR email LIKE '%$search_value%' OR number LIKE '%$search_value%'" : ""));
$food_total_row = mysqli_fetch_assoc($food_total_result);
$food_total = $food_total_row['total'] ?? 0;

// Calculate total price for catering orders
$cater_total_result = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM order_cater" . (isset($search_value) ? " WHERE full_name LIKE '%$search_value%' OR number1 LIKE '%$search_value%' OR email LIKE '%$search_value%' OR loc_ven LIKE '%$search_value%' OR full_address LIKE '%$search_value%' OR date1 LIKE '%$search_value%'" : ""));
$cater_total_row = mysqli_fetch_assoc($cater_total_result);
$cater_total = $cater_total_row['total'] ?? 0;

// Grand total
$grand_total = $food_total + $cater_total;

if (isset($_POST['search'])) {
    $search_value = mysqli_real_escape_string($conn, $_POST['search']);
    
    // Search in orders table
    $orders_query .= " WHERE name LIKE '%$search_value%' OR purok LIKE '%$search_value%' OR email LIKE '%$search_value%' OR number LIKE '%$search_value%'";

    // Search in catering orders table
    $cater_query .= " WHERE full_name LIKE '%$search_value%' OR number1 LIKE '%$search_value%' OR email LIKE '%$search_value%' OR loc_ven LIKE '%$search_value%' OR full_address LIKE '%$search_value%' OR date1 LIKE '%$search_value%'";
}
// Delete catering order
if (isset($_POST['delete_cater_order']) && isset($_POST['order_id'])) {
    $delete_id = $_POST['order_id'];
    mysqli_query($conn, "DELETE FROM `order_cater` WHERE id = '$delete_id'");
    header('location:order_user.php');
    exit;
}

// Delete food order
if (isset($_POST['delete_food_order']) && isset($_POST['order_id'])) {
    $delete_id = $_POST['order_id'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'");
    header('location:order_user.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Shopping Cart</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
   <link rel="stylesheet" href="css/style1.css" />
</head>
<body style="font-family: Arial, sans-serif; font-size: 12px; margin: 0; background: #f9f9f9;">

<!-- New Styled Header -->
<header style="background:#2c3e50; color:#fff; padding: 20px 40px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div style="font-size:24px; font-weight:bold;">FOOD ORDERING AND CATERING SYSTEM</div>
      <nav style="display:flex; gap:20px; align-items:center; flex-wrap:wrap;">
         <a href="admin.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">Menu</a>
         <a href="catering.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">Catering Menu</a>
         <a href="order_user.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">
            Orders
            <span style="background:red; color:white; padding:2px 8px; border-radius:12px; font-size:0.85em;">
               <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `orders`")); ?>
            </span>
         </a>
         <a href="user_display.php" style="color:#ecf0f1; text-decoration:none; font-weight:500;">
            Users
            <span style="background:red; color:white; padding:2px 8px; border-radius:12px; font-size:0.85em;">
               <?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `user_form`")); ?>
            </span>
         </a>
         <a href="logout.php" style="background:#e74c3c; padding:8px 16px; color:white; border-radius:5px; text-decoration:none; font-weight:500;">
            Logout
         </a>
      </nav>
   </div>
</header>
<!-- Side-by-Side Tables Container -->
<div style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center;">

   <!-- Order Card Style -->
   <style>
      .order-table-container {
         flex: 1 1 550px;
         background: #fff;
         border-radius: 12px;
         box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
         overflow-x: auto;
         padding: 20px;
      }

      .order-table {
         width: 100%;
         border-collapse: collapse;
         font-size: 13px;
      }

      .order-table thead {
         background-color: #2c3e50;
         color: white;
         position: sticky;
         top: 0;
         z-index: 1;
      }

      .order-table th,
      .order-table td {
         padding: 12px 10px;
         border-bottom: 1px solid #ecf0f1;
         text-align: left;
      }

      .order-table tbody tr:nth-child(even) {
         background-color: #f7f9fa;
      }

      .order-table tbody tr:hover {
         background-color: #eaf2f8;
      }

      .table-title {
         margin-bottom: 10px;
         color: #34495e;
         font-size: 18px;
         border-bottom: 2px solid #27ae60;
         padding-bottom: 6px;
      }

      .status-form select {
         padding: 6px 8px;
         border-radius: 4px;
         border: 1px solid #ccc;
         font-size: 12px;
      }

      .status-form button {
         padding: 6px 10px;
         background-color: #2980b9;
         color: white;
         border: none;
         border-radius: 4px;
         cursor: pointer;
         font-size: 12px;
      }

      .status-form {
         display: flex;
         gap: 6px;
         align-items: center;
         flex-wrap: wrap;
      }
   </style>

   <!-- Food Orders -->
   <div class="order-table-container">
      <h2 class="table-title">🍔 Food Orders</h2>
      <table class="order-table">
         <thead>
            <tr>
               <th>Full Name</th>
               <th>Address</th>
               <th>Email</th>
               <th>Number</th>
               <th>Orders</th>
               <th>Total Price</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_cart = mysqli_query($conn, $orders_query);
            if (mysqli_num_rows($select_cart) > 0) {
               while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            ?>
            <tr>
               <td><?php echo htmlspecialchars($fetch_cart['name']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['purok']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['email']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['number']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['total_products'] ); ?></td>
               <td>₱<?php echo number_format($fetch_cart['total_price'], 2); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['status']); ?></td>
   <td>
   <form method="post" class="status-form">
      <input type="hidden" name="order_id" value="<?php echo $fetch_cart['id']; ?>">
      <input type="hidden" name="type" value="orders">
      <select name="status">
         <option value="delivered" <?php if ($fetch_cart['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
         <option value="on the way" <?php if ($fetch_cart['status'] == 'on the way') echo 'selected'; ?>>On the Way</option>
         <option value="Cancelled" <?php if ($fetch_cart['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
      </select>
      <button type="submit" name="update_status_btn">Update</button>
   </form>
   <form method="post" style="margin-top:5px;" onsubmit="return confirm('Are you sure you want to delete this order?');">
      <input type="hidden" name="order_id" value="<?php echo $fetch_cart['id']; ?>">
      <button type="submit" name="delete_food_order" style="background:#e74c3c; color:white; padding:6px 10px; border:none; border-radius:4px; cursor:pointer; font-size:12px;">Delete</button>
   </form>
</td>

</td>

            </tr>
            <?php
               }
            } else {
               echo '<tr><td colspan="8" style="text-align:center; color:#999;">No food orders found.</td></tr>';
            }
            ?>
         </tbody>
      </table>
   </div>

   <!-- Catering Orders -->
   <div class="order-table-container">
      <h2 class="table-title">🥗 Catering Orders</h2>
      <table class="order-table">
         <thead>
            <tr>
               <th>Full Name</th>
               <th>Number</th>
               <th>Email</th>
               <th>Venue</th>
               <th>Address</th>
               <th>Date</th>
               <th>Total</th>
               <th>Status</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_cart = mysqli_query($conn, $cater_query);
            if (mysqli_num_rows($select_cart) > 0) {
               while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            ?>
            <tr>
               <td><?php echo htmlspecialchars($fetch_cart['full_name']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['number1']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['email']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['loc_ven']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['full_address']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['date1']); ?></td>
               <td>₱<?php echo number_format($fetch_cart['total_price']); ?></td>
               <td><?php echo htmlspecialchars($fetch_cart['status']); ?></td>
   <td>
   <form method="post" class="status-form">
      <input type="hidden" name="order_id" value="<?php echo $fetch_cart['id']; ?>">
     <input type="hidden" name="type" value="order_cater">
      <select name="status">
         <option value="delivered" <?php if ($fetch_cart['status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
         <option value="on the way" <?php if ($fetch_cart['status'] == 'on the way') echo 'selected'; ?>>On the Way</option>
         <option value="Cancelled" <?php if ($fetch_cart['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
      </select>
      <button type="submit" name="update_status_btn">Update</button>
   </form>
   <form method="post" style="margin-top:5px;" onsubmit="return confirm('Are you sure you want to delete this order?');">
      <input type="hidden" name="order_id" value="<?php echo $fetch_cart['id']; ?>">
      <button type="submit" name="delete_cater_order"
 style="background:#e74c3c; color:white; padding:6px 10px; border:none; border-radius:4px; cursor:pointer; font-size:12px;">Delete</button>
   </form>
</td>

</td>

            </tr>
            <?php
               }
            } else {
               echo '<tr><td colspan="9" style="text-align:center; color:#999;">No catering orders found.</td></tr>';
            }
            ?>
         </tbody>
      </table>
      
   </div>

</div>
<div style="text-align: center; margin: 40px auto; max-width: 500px; background: #ecf0f1; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
   <h2 style="color:#2c3e50; margin-bottom: 10px;">Order Totals</h2>
   <p style="font-size: 16px; color: #333;"><strong>🍔 Total Food Orders:</strong> ₱<?php echo number_format($food_total, 2); ?></p>
   <p style="font-size: 16px; color: #333;"><strong>🥗 Total Catering Orders:</strong> ₱<?php echo number_format($cater_total, 2); ?></p>
   <hr style="margin: 15px 0;">
   <p style="font-size: 18px; color: #27ae60; font-weight: bold;"><strong>🧾 Grand Total:</strong> ₱<?php echo number_format($grand_total, 2); ?></p>
</div>

</html>
