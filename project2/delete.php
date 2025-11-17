<?php
session_start();
require_once 'settings.php'; // kết nối DB

// Kiểm tra nếu có id được gửi qua GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // chuyển sang số nguyên để an toàn

    // Chuẩn bị câu lệnh SQL
    $sql = "DELETE FROM eoi WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Xóa thành công → quay về trang manage
        header("Location: manage.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request!";
}

$conn->close();