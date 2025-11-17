<?php
session_start();
require_once 'settings.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === "" || $password === "") {
        $error = "Vui lòng nhập Email và Mật khẩu.";
    } else {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_logged'] = true;
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Sai email hoặc mật khẩu.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
</head>

<body>

    <h2>Login</h2>

    <form method="POST">
        Email:<br>
        <input type="email" name="email"><br><br>

        Password:<br>
        <input type="password" name="password"><br><br>

        <button type="submit">Login</button>
    </form>

    <p style="color:red;"><?= $error ?></p>

</body>

</html>