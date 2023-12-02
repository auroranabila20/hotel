<?php

include 'project/components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
   header('location:index.php');
}

if(isset($_POST['check'])){

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   // Jika hotel punya total sejumlah 30 
   if($total_rooms >= 30){
      $warning_msg[] = 'Kamar tidak tersedia';
   }else{
      $success_msg[] = 'kamar tersedia';
   }

}

if(isset($_POST['book'])){

   $booking_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $rooms = $_POST['rooms'];
   $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
   $check_out = $_POST['check_out'];
   $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
   $adults = $_POST['adults'];
   $adults = filter_var($adults, FILTER_SANITIZE_STRING);
   $childs = $_POST['childs'];
   $childs = filter_var($childs, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   if($total_rooms >= 30){
      $warning_msg[] = 'kamar tidak tersedia';
   }else{

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      if($verify_bookings->rowCount() > 0){
         $warning_msg[] = 'tiket pesanan telah dipesan!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'tiket pesanan berhasil dipesan!';
      }

   }

}

if(isset($_POST['send'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->execute([$name, $email, $number, $message]);

   if($verify_message->rowCount() > 0){
      $warning_msg[] = 'Pesan telah terkirim!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $number, $message]);
      $success_msg[] = 'Pesan sukses terkirim!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="project/style/style.css">

</head>
<body>

<?php include 'project/components/user_header.php'; ?>

<!-- home section starts  -->

<section class="home" id="home">

   
            <img src="project/images/10.jpg" alt="">
            <div class="flex">
               <a href="#availability" class="btn">Lihat Ketersediaan</a>
            </div>
         </div>

         
            <img src="project/images/24.jpg" alt="">
            <div class="flex">
               <a href="#reservation" class="btn">Melakukan Reservasi</a>
            </div>
         </div>

         
            <img src="project/images/home-img-3.jpg" alt="">
            <div class="flex">
               <h3>Ruang Desain Mewah</h3>
               <a href="#contact" class="btn">Hubungi Kami</a>
            </div>
         </div>

      </div>

      

   </div>

</section>

<!-- home section ends -->

<!-- availability section starts  -->

<section class="availability" id="availability">
   <form action="" method="post">
      <div class="flex">
         <div class="box">
            <p>Check In <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>Check Out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>Orang Dewasa <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1">1 Orang Dewasa</option>
               <option value="2">2 Orang Dewasa</option>
               <option value="3">3 Orang Dewasa</option>
               <option value="4">4 Orang Dewasa</option>
               <option value="5">5 Orang Dewasa</option>
               <option value="6">6 Orang Dewasa</option>
            </select>
         </div>
         <div class="box">
            <p>Anak <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="-">0 Anak</option>
               <option value="1">1 Anak</option>
               <option value="2">2 Anak</option>
               <option value="3">3 Anak</option>
               <option value="4">4 Anak</option>
               <option value="5">5 Anak</option>
               <option value="6">6 Anak</option>
            </select>
         </div>
         <div class="box">
            <p>Kamar <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1">1 Kamar</option>
               <option value="2">2 Kamar</option>
               <option value="3">3 Kamar</option>
               <option value="4">4 Kamar</option>
               <option value="5">5 Kamar</option>
               <option value="6">6 Kamar</option>
            </select>
         </div>
      </div>
      <input type="submit" value="Periksa Ketersediaan" name="check" class="btn">
   </form>

</section>

<!-- availability section ends -->

<!-- about section starts  -->

<section class="about" id="about">

   <div class="row">
      <div class="image">
         <img src="project/images/about-img-1.jpeg" alt="">
      </div>
      <div class="content">
         <h3>Staff Terbaik</h3>
         <p>"Best staff" di Galactic Dreams Hotel selalu siap memberikan perhatian khusus kepada setiap tamu. Mereka dengan senang hati menjawab pertanyaan, memberikan rekomendasi wisata, atau mengatasi permintaan khusus. Dedikasi mereka terhadap kenyamanan tamu adalah salah satu pilar kesuksesan hotel ini.</p>
         <a href="#reservation" class="btn">pesan kamar</a>
      </div>
   </div>

   <div class="row revers">
      <div class="image">
         <img src="project/images/about-img-2.jpeg" alt="">
      </div>
      <div class="content">
         <h3>Makanan Terfavorit</h3>
         <p>di Galactic Dreams Hotel adalah kombinasi dari ragam menu yang luar biasa, bahan-bahan berkualitas tinggi, inovasi kuliner, pelayanan yang ramah, dan suasana yang memikat. Ini adalah tempat yang sempurna bagi para pecinta kuliner yang ingin menjelajahi cita rasa baru dan merayakan makanan yang luar biasa selama masa menginap mereka.</p>
         <a href="#contact" class="btn">Hubungi Kami</a>
         <a href="project/components/lihatmenu.php" class="btn">Lihat Menu</a>

      </div>
   </div>

   <div class="row">
      <div class="image">
         <img src="project/images/about-img-3.jpeg" alt="">
      </div>
      <div class="content">
         <h3>Kolam Renang</h3>
         <p>Kolam renang di Galactic Dreams Hotel adalah tempat yang benar-benar istimewa. Anda tidak hanya akan berenang, tetapi juga merasa seperti berada di luar angkasa. Dengan desain futuristik, pencahayaan yang menakjubkan, dan dinding kaca, Anda akan merasa seolah-olah berenang di antara bintang-bintang.</p>
         <a href="#availability" class="btn">Periksa ketersediaan</a>
      </div>
   </div>

</section>

<!-- about section ends -->

<!-- services section starts  -->

<section class="services">

   <div class="box-container">

      <div class="box">
         <img src="project/images/icon-1.png" alt="">
         <h3>food & drinks</h3>
         <p>"Best food and drink" di hotel ini selalu dipersiapkan dengan menggunakan bahan-bahan berkualitas tinggi. Tim koki hotel berusaha untuk menghadirkan hidangan yang segar dan bermutu tinggi, sehingga setiap gigitan menjadi sebuah pengalaman yang luar biasa.</p>
      </div>

      <div class="box">
         <img src="project/images/icon-2.png" alt="">
         <h3>outdoor dining</h3>
         <p>Restoran di Galactic Dreams Hotel seringkali memiliki suasana yang memikat. Tamu dapat makan dengan latar belakang pemandangan yang spektakuler atau menikmati makanan di ruang makan yang mewah. Suasana yang unik ini menambahkan elemen eksklusif pada pengalaman makan.</p>
      </div>

      <div class="box">
         <img src="project/images/icon-3.png" alt="">
         <h3>beach view</h3>
         <p>Galactic Dreams Hotel, tempat di mana kemewahan bertemu dengan keindahan alam, membanggakan pemandangan pantai yang tak tertandingi di seluruh dunia. Dari setiap sudut kamar yang didesain dengan cermat, Anda akan disuguhkan dengan pemandangan langsung ke lautan yang memukau. Inilah yang kami sebut sebagai "beach view" yang menakjubkan.</p>
      </div>

      <div class="box">
         <img src="project/images/icon-4.png" alt="">
         <h3>decorations</h3>
         <p>Hotel ini dirancang dengan elegan, menciptakan lingkungan yang berkelas dan mewah bagi setiap tamu.</p>
      </div>

      <div class="box">
         <img src="project/images/icon-5.png" alt="">
         <h3>swimming pool</h3>
         <p>Kami selalu menjaga suhu air yang nyaman, jadi Anda dapat berenang sepanjang tahun. Di sekitar kolam, ada bar terapung dan tempat duduk yang nyaman untuk bersantai. Juga, kami kadang-kadang mengadakan kelas yoga di sini.</p>
</div>
   </div>

</section>

<!-- services section ends -->

<!-- reservation section starts  -->

<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>Pemesanan Kamar</h3>
      <div class="flex">
         <div class="box">
            <p>nama<span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="masukkan nama" class="input">
         </div>
         <div class="box">
            <p> email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="masukkan kata sandi" class="input">
         </div>
         <div class="box">
            <p>nomer hp<span>*</span></p>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="masukkan nomor hp" class="input">
         </div>
         <div class="box">
            <p>Kamar<span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 Kamar</option>
               <option value="2">2 Kamar</option>
               <option value="3">3 Kamar</option>
               <option value="4">4 Kamar</option>
               <option value="5">5 Kamar</option>
               <option value="6">6 Kamar</option>
            </select>
         </div>
         <div class="box">
            <p>Daftar Kamar <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>Lihat Pesanan<span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>Orang Dewasa<span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 Orang Dewasa</option>
               <option value="2">2 Orang Dewasa</option>
               <option value="3">3 Orang Dewasa</option>
               <option value="4">4 Orang Dewasa</option>
               <option value="5">5 Orang Dewasa</option>
               <option value="6">6 Orang Dewasa</option>
            </select>
         </div>
         <div class="box">
            <p>Anak <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 Anak</option>
               <option value="1">1 Anak</option>
               <option value="2">2 Anak</option>
               <option value="3">3 Anak</option>
               <option value="4">4 Anak</option>
               <option value="5">5 Anak</option>
               <option value="6">6 Anak</option>
            </select>
         </div>
      </div>
      <input type="submit" value="sewa sekarang" name="book" class="btn">
   </form>

</section>

<!-- reservation section ends -->

<!-- gallery section starts  -->

<section class="gallery" id="gallery">

  
         <img src="project/images/15.jpg" class="" alt="">
         <img src="project/images/11.jpg" class="" alt="">
         <img src="project/images/10.jpg" class="" alt="">
         <img src="project/images/18.jpg" class="" alt="">
         <img src="project/images/17.jpg" class="" alt="">
         <img src="project/images/13.jpg" class="" alt="">
      </div>

   </div>

</section>

<!-- gallery section ends -->

<!-- contact section starts  -->

<section class="contact" id="contact">

   <div class="row">

      <form action="" method="post">
         <h3>kirim kami pesan</h3>
         <input type="text" name="name" required maxlength="50" placeholder="masukkan nama" class="box">
         <input type="email" name="email" required maxlength="50" placeholder="masukkan email" class="box">
         <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="masukkan nomor hp" class="box">
         <textarea name="message" class="box" required maxlength="1000" placeholder="masukkan pesan" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" name="send" class="btn">
      </form>

      <div class="faq">
         <h3 class="title">Pertanyaan Yang Sering Diajukan</h3>
         <div class="box active">
            <h3>bagaimana cara membatalkan?</h3>
            <p>anda bisa mengirimi kami pesan melalui kolom pesan kepada operator hotel kami. Buatkan permintaan anda disertai dengan data diri.</p>
         </div>
         <div class="box">
            <h3>Apakah ada lowongan pekerjaan?</h3>
            <p>Di hotel kami menerima lowongan pekerjaan untuk staff hotel.</p>
         </div>
         <div class="box">
            <h3>Apakah ada persyaratan umur?</h3>
            <p>Ada. Kami memiliki persyaratan umur namun tanpa ada batasan. Ada dua opsi saja yaitu Anak-anak dan orang dewasa.</p>
         </div>
      </div>

   </div>

</section>

<!-- contact section ends -->

<!-- reviews section starts  -->

<section class="reviews" id="reviews">

   
            
            <h3>Fauzan hardidinata</h3>
            <p>Selama saya pergi ke luar kota. Galactic Dreams Hotel menjadi tempat andalan terbaik saya untuk menginap. Selain fasilitasnya mewah.</p>
         </div>

            <h3>Meyriska key</h3>
            <p>Hotelnya bagus banget!.</p>
         
   </div>

</section>

<!-- reviews section ends  -->





<?php include 'project/components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'project/components/message.php'; ?>

</body>
</html>