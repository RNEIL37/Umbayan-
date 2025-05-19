
<?php
@include 'config.php';

// Update order quantity
if (isset($_POST['update_update_btn'])) {
    $update_value = $_POST['update_quantity'];
    $update_id = $_POST['update_quantity_id'];
    $update_quantity_query = mysqli_query($conn, "UPDATE `orders` SET quantity = '$update_value' WHERE id = '$update_id'");
    if ($update_quantity_query) {
        header('location:user_notif.php');
    }
}

// Approve order
if (isset($_GET['Approve'])) {
    $Approve_id = $_GET['Approve'];
    mysqli_query($conn, "UPDATE `orders` SET status = 'Approved' WHERE id = '$Approve_id'");
    header('location:user_notif.php');
}

// Delete all orders
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `orders`");
    header('location:user_notif.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Your Orders</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="margin:0; font-family:'Segoe UI', sans-serif; background:#f5f5f5; color:#333;">

<header style="background:#2c3e50; color:#fff; padding:20px 40px;">
   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap;">
      <div style="font-size:1.5em; font-weight:bold;">FOOD ORDERING AND CATERING SYSTEM</div>
      <nav style="display:flex; gap:20px; flex-wrap:wrap;">
         <a href="products.php" style="color:#ecf0f1; text-decoration:none;">Menu</a>
         <a href="products2.php" style="color:#ecf0f1; text-decoration:none;">Catering Menu</a>
         <a href="cart1.php" style="color:#ecf0f1; text-decoration:none;">Cart</a>
         <a href="user_notif.php" style="color:#ecf0f1; text-decoration:none;">Notifications 
            <span style="background:red; color:white; padding:2px 8px; border-radius:10px;">
               <?php 
               $select_rows = mysqli_query($conn, "SELECT * FROM `order_cater`") or die('query failed');
               echo mysqli_num_rows($select_rows); 
               ?>
            </span>
         </a>
         <a href="logout.php" style="background:#e74c3c; padding:6px 12px; color:white; border-radius:5px; text-decoration:none;">Logout</a>
      </nav>
   </div>
</header>
    
<div style="padding: 40px; max-width: 1200px; margin: auto;">
    
    <section class="shopping-cart">
        <h1 style="text-align: center; font-size: 36px; margin-bottom: 30px;">Your Orders</h1>
        <table style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 16px; background-color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">

            <thead style="background-color: #2c3e50; color: white;">
                <tr>
                    <th style="padding: 14px; text-align: left;">Full Name</th>
                    <th style="padding: 14px; text-align: left;">Purok</th>
                    <th style="padding: 14px; text-align: left;">Barangay</th>
                    <th style="padding: 14px; text-align: left;">Municipal</th>
                    <th style="padding: 14px; text-align: left;">Province</th>
                    <th style="padding: 14px; text-align: left;">Total Price</th>
                    <th style="padding: 14px; text-align: left;">Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $select_cart = mysqli_query($conn, "SELECT * FROM `orders`");
                $row_counter = 0;
                $grand_total = 0;

                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $bg_color = ($row_counter % 2 == 0) ? "#f9f9f9" : "#ffffff";
                        $grand_total += $fetch_cart['total_price'];

                        echo '<tr style="background-color: ' . $bg_color . '; transition: background-color 0.3s;" 
                            onmouseover="this.style.backgroundColor=\'#ecf0f1\'" 
                            onmouseout="this.style.backgroundColor=\'' . $bg_color . '\'">';

                        echo '<td style="padding: 14px;">' . $fetch_cart['name'] . '</td>';
                        echo '<td style="padding: 14px;">' . $fetch_cart['purok'] . '</td>';
                        echo '<td style="padding: 14px;">' . $fetch_cart['barangay'] . '</td>';
                        echo '<td style="padding: 14px;">' . $fetch_cart['municipal'] . '</td>';
                        echo '<td style="padding: 14px;">' . $fetch_cart['province'] . '</td>';
                        echo '<td style="padding: 14px;">₱' . $fetch_cart['total_price'] . '</td>';
                        echo '<td style="padding: 14px;">' . $fetch_cart['status'] . '</td>';

                        echo '</tr>';
                        $row_counter++;
                    }
                } else {
                    echo '<tr><td colspan="7" style="text-align:center; padding: 20px; color: gray;">No orders found.</td></tr>';
                }
                ?>
            </tbody>

            <?php if ($grand_total > 0): ?>
            <tfoot>
                <tr style="background-color:#ecf0f1;">
                    <td colspan="5" style="text-align:right; padding: 14px; font-weight:bold;">Grand Total:</td>
                    <td colspan="2" style="padding: 14px; font-weight:bold;">₱<?php echo number_format($grand_total, 2); ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>

        </table>
    </section>
</div>

<!-- Sticky Footer -->
<footer style="background-color: #2c3e50; color: #ecf0f1; padding: 30px 20px; text-align: center; font-size: 15px; border-top: 4px solid #27ae60;">
   <div style="max-width: 1000px; margin: auto;">
      <p style="margin: 5px 0;">📧 Email: 
         <a href="mailto:kylenoelcalamba@gmail.com" style="color: #1abc9c; text-decoration: none;">kylenoelcalamba@gmail.com</a>
      </p>
      <p style="margin: 5px 0;">📞 Phone: 
         <a href="tel:09261536225" style="color: #1abc9c; text-decoration: none;">0926-153-6225</a>
      </p>
      <hr style="border: 0; border-top: 1px solid #7f8c8d; margin: 20px auto; width: 60%;">
      <p style="margin: 0;">&copy; 2027 <strong>Food Ordering and Catering System</strong> — All Rights Reserved</p>
   </div>
</footer>
</body>
</html>
