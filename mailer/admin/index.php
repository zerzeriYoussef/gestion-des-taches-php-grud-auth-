<?php
session_start();
    if(isset($_SESSION['user'])){
        if($_SESSION['user']->ROLE==="ADMIN"){
            echo 'Welcome ' .$_SESSION['user']->NAME;
            echo "<form > <button type='submit' name='logout'>deconnexion</button></form>";
        }else{
            header("http://localhost:3000/login.php",true);
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