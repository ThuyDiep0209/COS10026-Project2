user.php
<?php 
    session_start();
    require_once('settings.php');
    
    
        $username1 = trim($_POST['username']);
        $password1 = trim($_POST['password']);
        $sql = "SELECT * FROM users WHERE username = '$username1' AND password = '$password1'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        

            if ($row){
                $_SESSION['username'] = $username;
                header('Location: welcome.php');
            }else{
                echo"Login failed. <a href='user_login.php'>Try again</a>";
               
            }
        
    
?>