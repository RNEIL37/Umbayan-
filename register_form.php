<?php
@include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);

   $user_type = 'user';  // Force user_type to "user" only

   // Check if email already exists (regardless of password)
   $select = "SELECT * FROM user_form WHERE email = '$email'";
   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){
      $error[] = 'User already exists!';
   } else {
      if($pass != $cpass){
         $error[] = 'Passwords do not match!';
      } else {
         $insert = "INSERT INTO user_form(name, email, password, user_type) VALUES('$name','$email','$pass','$user_type')";
         mysqli_query($conn, $insert);
         header('location:login_form.php');
         exit;
      }
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Register Form</title>

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
         background: rgba(255, 255, 255, 0.95);
         border-radius: 1rem;
         box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
         padding: 2rem;
         width: 100%;
         max-width: 500px;
      }

      .form-label {
         font-weight: 500;
      }

      .btn-primary {
         background-color: #ee0979;
         border: none;
      }

      .btn-primary:hover {
         background-color: #d1066b;
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
   <h3 class="text-center mb-4">Register Now</h3>

   <?php
   if(isset($error)){
      foreach($error as $err){
         echo '<div class="alert alert-danger text-center">'.$err.'</div>';
      }
   }
   ?>

   <form action="" method="post">
      <div class="mb-3">
         <label for="name" class="form-label">Name</label>
         <input type="text" name="name" id="name" class="form-control" required placeholder="Enter your name" />
      </div>

      <div class="mb-3">
         <label for="email" class="form-label">Email</label>
         <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email" />
      </div>

      <div class="mb-3">
         <label for="password" class="form-label">Password</label>
         <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password" />
      </div>

      <div class="mb-3">
         <label for="cpassword" class="form-label">Confirm Password</label>
         <input type="password" name="cpassword" id="cpassword" class="form-control" required placeholder="Confirm your password" />
      </div>

      <!-- Removed user_type selection -->

      <div class="d-grid">
         <button type="submit" name="submit" class="btn btn-primary">Register Now</button>
      </div>

      <div class="mt-3 text-center">
         <p>Already have an account? <a href="login_form.php">Login now</a></p>
      </div>
   </form>
</div>

</body>
</html>
