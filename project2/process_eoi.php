<?php
// Bắt đầu session 
session_start();

// Kết nối tới database
require_once 'settings.php';
// Kiểm tra xem form có submit không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Nhận và trim dữ liệu từ form
    $jobref = trim($_POST['jobref'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $suburb = trim($_POST['suburb'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $postcode = trim($_POST['postcode'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $skills = $_POST['skills'] ?? [];
    $others = trim($_POST['others'] ?? '');

    // Nếu skills là mảng, nối thành chuỗi
    $skills_str = implode(", ", $skills);

    // Chuẩn bị SQL
    $sql = "INSERT INTO eoi 
        (jobref, fname, lname, dob, gender, street, suburb, state, postcode, email, phone, skills, others)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssssss",
        $jobref,
        $fname,
        $lname,
        $dob,
        $gender,
        $street,
        $suburb,
        $state,
        $postcode,
        $email,
        $phone,
        $skills_str,
        $others
    );

    // Thực thi
    if ($stmt->execute()) {
        // Chuyển hướng sang trang thank_you.php
        header("Location: manage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Đóng statement
    $stmt->close();
}

// Đóng kết nối
$conn->close();