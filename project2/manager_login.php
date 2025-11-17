<?php
session_start();
require_once "settings.php";

// Nếu đã login rồi
if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true) {
    header("Location: manage.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Lấy user từ DB
    $sql = "SELECT id, username, password_hash, failed_attempts, locked_until 
            FROM managers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("SQL Error: " . $conn->error);

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    // User không tồn tại
    if ($res->num_rows === 0) {
        $message = "<span style='color:red;'>Invalid username or password.</span>";
    } else {

        $user = $res->fetch_assoc();

        // CHECK LOCK
        if ($user['locked_until'] !== null && strtotime($user['locked_until']) > time()) {
            $wait = strtotime($user['locked_until']) - time();
            $message = "<span style='color:red;'>Account locked. Try again in $wait seconds.</span>";
        } else {
            // Kiểm tra mật khẩu
            if (password_verify($password, $user['password_hash'])) {

                // RESET failed_attempts + locked_until
                $resetSql = "UPDATE managers SET failed_attempts = 0, locked_until = NULL WHERE id = ?";
                $resetStmt = $conn->prepare($resetSql);
                $resetStmt->bind_param("i", $user['id']);
                $resetStmt->execute();

                // Login thành công
                $_SESSION['manager_logged_in'] = true;
                $_SESSION['manager_username'] = $user['username'];

                header("Location: manage.php");
                exit();
            } else {
                // PASSWORD SAI → tăng failed_attempts
                $newFails = $user['failed_attempts'] + 1;

                if ($newFails >= 3) {
                    // Lock account 5 phút
                    $lockUntil = date("Y-m-d H:i:s", time() + 300);

                    $update = "UPDATE managers 
                               SET failed_attempts = ?, locked_until = ?
                               WHERE id = ?";
                    $stmt2 = $conn->prepare($update);
                    $stmt2->bind_param("isi", $newFails, $lockUntil, $user['id']);
                    $stmt2->execute();

                    $message = "<span style='color:red;'>Too many attempts. Locked for 5 minutes.</span>";
                } else {
                    // Chỉ tăng failed_attempts
                    $update = "UPDATE managers SET failed_attempts = ? WHERE id = ?";
                    $stmt2 = $conn->prepare($update);
                    $stmt2->bind_param("ii", $newFails, $user['id']);
                    $stmt2->execute();

                    $message = "<span style='color:red;'>Invalid username or password.</span>";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/manage.css">
</head>

<body>

    <?php include "header.inc"; ?>

    <main id="main_login">
        <h1>Manager Login</h1>

        <?= $message ?>


        <form method="post" action="" id="form_login">
            <label>Username:<br>
                <input type="text" name="username" id="login" required>
            </label>

            <label>Password:<br>
                <input type="password" name="password" id="pw" required>
            </label>

            <button type="submit" id="btn_login">Login</button>
        </form>

        <p><a href="manager_register.php">Register manager account</a></p>
    </main>

    <?php include "footer.inc"; ?>

</body>

</html>