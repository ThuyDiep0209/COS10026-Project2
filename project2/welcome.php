<?php 
    session_start();
    if(!$_SESSION['username']){
        header('Location: user_login.php');
        exit();
    }else{echo"Welcome", $_SESSION['username']," to 2025.";}
?>