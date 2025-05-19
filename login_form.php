<?php
@include 'config.php';
session_start(); // Start session to manage user sessions

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);

   $select = "SELECT * FROM user_form WHERE email = '$email' AND password = '$pass'";
   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $row = mysqli_fetch_assoc($result);

      // Set session variables for logged in user
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['name'];
      $_SESSION['user_email'] = $row['email'];
      $_SESSION['user_type'] = $row['user_type'];

      // Redirect based on user_type
      if($row['user_type'] == 'admin'){
         header('location:admin.php');
         exit;
      } elseif($row['user_type'] == 'delivery'){
         header('location:delivery_order.php');  // You can change this to your actual delivery page
         exit;
      } elseif($row['user_type'] == 'user'){
         	session_start();//para mag start ang session
		      $_SESSION['id']=$row['id'];//
         header('location:products.php?id='.$_SESSION['id'].'');
         exit;
      } else {
         // Optional: if user_type is unknown, log out or show error
         $error[] = 'Unknown user role!';
      }

   } else {
      $error[] = 'Incorrect email or password!';
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Login Form</title>

   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />

   <style>
      body {
         font-family: 'Poppins', sans-serif;
         background: linear-gradient(135deg, #667eea, #764ba2);
         min-height: 100vh;
         display: flex;
         align-items: center;
         justify-content: center;
         margin: 0;
         padding: 0;
      }

      .card {
         backdrop-filter: blur(8px);
         background-color: rgba(255, 255, 255, 0.85);
         border-radius: 1rem;
         box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
         padding: 2rem;
         width: 100%;
         max-width: 450px;
      }

      .btn-primary {
         background-color: #ee0979;
         border: none;
      }

      .btn-primary:hover {
         background-color: #434190;
      }

      .form-label {
         font-weight: 500;
      }

      .alert {
         font-size: 0.9rem;
         padding: 0.5rem 1rem;
         margin-bottom: 1rem;
      }
   </style>
</head>
<body>

<div class="card">
   <h3 class="text-center mb-4">Login Now</h3>

   <?php
   if(isset($error)){
      foreach($error as $err){
         echo '<div class="alert alert-danger text-center">'.$err.'</div>';
      }
   }
   ?>

   <form action="" method="post">
      <div class="mb-3">
         <label for="email" class="form-label">Email address</label>
         <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email" />
      </div>

      <div class="mb-3">
         <label for="password" class="form-label">Password</label>
         <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password" />
      </div>

      <div class="d-grid">
         <button type="submit" name="submit" class="btn btn-primary">Login Now</button>
      </div>

      <div class="mt-3 text-center">
         <p>Don't have an account? <a href="register_form.php">Register now</a></p>
      </div>
   </form>
</div>

</body>
</html>
