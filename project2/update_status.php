<?php
session_start();
require_once 'settings.php';

if (!isset($_SESSION['manager_logged_in'])) {
    header("Location: manager_login.php");
    exit();
}

// CSRF
if ($_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    die("Invalid CSRF token");
}

$id = intval($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

$allowed = ['New', 'Current', 'Final'];
if ($id <= 0 || !in_array($status, $allowed)) {
    header("Location: manage.php?msg=error");
    exit();
}

$sql = "UPDATE eoi SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
$ok = $stmt->execute();

$stmt->close();
$conn->close();

if ($ok) header("Location: manage.php?msg=status");
else header("Location: manage.php?msg=error");
exit();