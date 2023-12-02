<?php

@include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $user_type = $_POST['user_type'];

   $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if(mysqli_num_rows($result) > 0){

      $error[] = 'pengguna sudah ada!';

   }else{

      if($pass != $cpass){
         $error[] = 'Kata sandi tidak sesuai!';
      }else{
         $insert = "INSERT INTO user_form(name, email, password, user_type) VALUES('$name','$email','$pass','$user_type')";
         mysqli_query($conn, $insert);
         header('location:index.php');
      }
   }

};


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrasi Akun</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>Registrasi Sekarang</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="name" required placeholder="masukkan nama ">
      <input type="email" name="email" required placeholder="masukkan mail">
      <input type="password" name="password" required placeholder="masukkan kata sandi">
      <input type="password" name="cpassword" required placeholder="konfirmasi kata sandi">
      <select name="user_type">
         <option value="user">Pengguna</option>
         <option value="admin">Pengelola</option>
      </select>
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>Belum Punya Akun? <a href="index.php">Masuk SSekarang</a></p>
   </form>

</div>

</body>
</html>