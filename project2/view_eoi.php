<?php
session_start();
require_once 'settings.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo "No EOI selected.";
    exit;
}

$sql = "SELECT * FROM eoi WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "<h1>EOI Details</h1>";
    echo "<p><strong>Job Ref:</strong> " . htmlspecialchars($row['jobref']) . "</p>";
    echo "<p><strong>First Name:</strong> " . htmlspecialchars($row['fname']) . "</p>";
    echo "<p><strong>Last Name:</strong> " . htmlspecialchars($row['lname']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
    echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</p>";
    echo "<p><strong>Submitted At:</strong> " . $row['submitted_at'] . "</p>";
} else {
    echo "EOI not found.";
}

$stmt->close();
$conn->close();
?>