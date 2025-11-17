<?php
session_start();
require_once 'settings.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username=?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username already exists!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ss", $username, $password_hash);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Sign up successful! <a href='user_login.php'>Login now</a>";
            } else {
                $error = "Something went wrong, please try again!";
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
</head>

<body>
    <h2>Create an Account</h2>
    <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if($success) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="post" action="">
        <label>Username:</label> <input type="text" name="username"><br><br>
        <label>Password:</label> <input type="password" name="password"><br><br>
        <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="user_login.php">Login</a></p>
</body>

</html>