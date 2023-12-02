<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['user_name'])){
   header('location:halaman.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Halaman Pengguna</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<div class="container">

   <div class="content">
      <h3>Halo, <span>Pengguna</span></h3>
      <h1>Selamat Datang <span><?php echo $_SESSION['user_name'] ?></span></h1>
      <p>di halaman Galactic Dreams Hotel pengguna</p>
      <a href="index.php" class="btn">kembali</a>
      <a href="register_form.php" class="btn">registrasi</a>
      <a href="logout.php" class="btn">keluar</a>
      <a href="halaman.php" class="btn">masuk</a>
   </div>

</div>

</body>
</html>