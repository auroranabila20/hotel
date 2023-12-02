<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['admin_name'])){
   header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Halaman Admin</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>
   
<div class="container">

   <div class="content">
      <h3>Halo <span>Admin</span></h3>
      <h1>Selamat Datang <span><?php echo $_SESSION['admin_name'] ?></span></h1>
      <p>Di Halaman Galactic Dreams Hotel Admin</p>
      <a href="index.php" class="btn">Kembali</a>
      <a href="register_form.php" class="btn">Registrasi</a>
      <a href="logout.php" class="btn">Keluar</a>
      <a href="project/admin/login.php" class="btn">Masuk</a>
   </div>

</div>

</body>
</html>