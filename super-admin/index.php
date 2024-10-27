<!DOCTYPE html>
<html >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<body>
    <!-- Image and text -->
<nav class="navbar navbar-light bg-light">

  <a class="navbar-brand" href="#">
        zarzour
  </a>

  <img src="../img/img1.jpg" width="50" height="50" class="d-inline-block align-top" alt="" loading="lazy">

</nav>

<main class="container m-auto" style="max-width: 720px;">


<?php
session_start();
if(isset($_SESSION['user'])){
if($_SESSION['user']->ROLE === "SUPER-ADMIN"){
echo '<div class="shadow p-3 mb-1 bg-white rounded mt-5"> Welcome ' .$_SESSION['user']->NAME . "</div>";
echo '<a  class="btn btn-light shadow w-100 mb-1" href="profile.php">تعديل ملف الشخصي</a>';
echo '<a  class="btn btn-light shadow w-100 mb-1" href="todolist.php">إضافة واجبات لقيام بها</a>';

echo "<form> <button class='btn btn-danger w-100' type='submit' name='logout'>تسجيل خروج</button></form>";
}else{
    header("location:http://localhost:3000/login.php",true); 
    die("");
}
}else{
    header("http://localhost:3000/login.php",true); 
    die(""); 
}

if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header("http://localhost:3000/login.php",true); 
    }
?> 
</main>
</body>
</html>


