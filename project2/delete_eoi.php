<?php
session_start();
require_once 'settings.php';

if (!isset($_SESSION['manager_logged_in'])) {
    header("Location: manager_login.php");
    exit();
}

if ($_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    die("Invalid CSRF token");
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    header("Location: manage.php?msg=error");
    exit();
}

$sql = "DELETE FROM eoi WHERE EOInumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$ok = $stmt->execute();

$stmt->close();
$conn->close();

if ($ok) header("Location: manage.php?msg=deleted");
else header("Location: manage.php?msg=error");
exit();