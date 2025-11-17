<?php
session_start();
require_once 'settings.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_logged'] = true;
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Username not found.";
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        <label>Username:</label> <input type="text" name="username"><br><br>
        <label>Password:</label> <input type="password" name="password"><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="user.php">Sign Up</a></p>
</body>

</html>