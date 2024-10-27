<?php require_once 'nav.php'; ?>  

<?php
require_once 'connectToDatabase.php';
echo' <main class="container mt-6" style="text-align: right!important;">
<form method="POST">

    <p class="font-weight-bold">بريد الكتروني</p>
    <input class="form-control" type="email" name="email" required/>


    <p class="font-weight-bold">كلمة المرور</p>
    <input class="form-control" type="password" name="password" required/>
    <a href="reset.php"> نسيت كلمة مرور؟</a>
    <br>
    <a class="btn btn-dark mt-3" href="register.php">تسجيل </a>
    <button class="btn btn-warning mt-3" type="submit" name="login">تسجيل دخول</button>

</form>

</main>';
if(isset($_POST['login'])){
    $login = $database->prepare("SELECT * FROM users WHERE EMAIL = :email AND PASSWORD = :password");
    $login->bindParam("email",$_POST['email']);
    //$passwordUser = sha1($_POST['password']);
    $login->bindParam("password",$_POST['password']);
    $login->execute();
    if($login->rowCount() === 1){
        $user = $login->fetchObject();
        if($user->ACTIVATED === 1){
            session_start();
            $_SESSION['user'] = $user;
            if($user->ROLE ==="USER"){
                header("location:https://zarzourpart12.000webhostapp.com/zarzourr/zarzour/app/user/index.php");
                exit();
            } else if($user->ROLE ==="ADMIN"){
                header("location:admin/index.php");
                exit();
            } else if($user->ROLE ==="SUPER-ADMIN"){
                header("location:super-admin/index.php");
                exit();
            }
        } else {
            echo '
            <div class="alert alert-warning"> 
            يرجى تفعيل حسابك في البداية, لقد ارسلنا رمز تحقق من حسابك إلى بريد الكتروني خاص بك
            </div>
            ';
        }
    } else {
        echo '
        <div class="alert alert-danger">
        كلمة مرور او بريد الكتروني غير صحيح 
        </div>
        ';   
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="css.css">

</head>
<body>
 
   
   

    <script>document.addEventListener('DOMContentLoaded', () => {
  var disclaimer =  document.querySelector("img[alt='www.000webhost.com']");
   if(disclaimer){
       disclaimer.remove();
   }  
 });</script>
</body>
</html>
