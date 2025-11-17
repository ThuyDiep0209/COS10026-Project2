<?php
session_start();
require_once "settings.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $message = "<p style='color:red;'>All fields are required.</p>";
    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO managers (username, password_hash) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) die("SQL Error: " . $conn->error);

        $stmt->bind_param("ss", $username, $hash);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>Manager account created. <a href='manager_login.php'>Login now</a></p>";
        } else {
            $message = "<p style='color:red;'>Username already exists.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Create Manager Account</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/manage.css">
</head>

<body>
    <?php include "header.inc"; ?>

    <main id="main_register">
        <h1>Create Manager Account</h1>

        <?= $message ?>

        <form method="post" action="" id="form_register">
            <label>Username:<br>
                <input type="text" name="username" id="reg_username" required>
            </label>

            <label>Password:<br>
                <input type="password" name="password" id="reg_password" required>
            </label>

            <button type="submit" id="btn_register">Register</button>
        </form>

        <p><a href="manager_login.php">Back to Login</a></p>
    </main>


    <?php include "footer.inc"; ?>
</body>

</html>