<?php
session_start();
require_once 'settings.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === "" || $email === "" || $password === "") {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hash);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Đăng ký thành công! Hãy đăng nhập.";
        } else {
            $error = "Email đã tồn tại hoặc lỗi hệ thống.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Register</title>
</head>

<body>

    <h2>Register</h2>

    <form method="POST">
        Name:<br>
        <input type="text" name="name"><br><br>

        Email:<br>
        <input type="email" name="email"><br><br>

        Password:<br>
        <input type="password" name="password"><br><br>

        <button type="submit">Register</button>
    </form>

    <p style="color:green;"><?= $success ?></p>
    <p style="color:red;"><?= $error ?></p>

</body>

</html>