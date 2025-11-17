<?php
// Bắt đầu session 
session_start();

// Kết nối tới database
require_once 'settings.php';
// Kiểm tra xem form có submit không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Nhận và trim dữ liệu từ form
    $job_ref = trim($_POST['jobref'] ?? '');
    $first_name = trim($_POST['fname'] ?? '');
    $last_name = trim($_POST['lname'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $street = trim($_POST['street'] ?? '');
    $suburb = trim($_POST['suburb'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $postcode = trim($_POST['postcode'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $skills = $_POST['skills'] ?? [];
    $other_skills = trim($_POST['others'] ?? '');

    // --- Server-side validation per requirements ---
    $errors = []; // associative: field => message

    // Validate jobref: non-empty and exists in jobs table
    if (empty($job_ref)) {
        $errors['jobref'] = 'Job reference is required.';
    } else {
        $q = $conn->prepare('SELECT COUNT(*) AS cnt FROM jobs WHERE job_ref = ?');
        if ($q) {
            $q->bind_param('s', $job_ref);
            $q->execute();
            $res = $q->get_result();
            $row = $res->fetch_assoc();
            if (!$row || intval($row['cnt']) === 0) {
                $errors['jobref'] = 'Selected job reference is not valid.';
            }
            $q->close();
        } else {
            $errors['jobref'] = 'Server error validating job reference.';
        }
    }

    // First and last name: alpha only, max 20
    if (empty($first_name) || !preg_match('/^[A-Za-z]{1,20}$/', $first_name)) {
        $errors['fname'] = 'First name must be 1-20 alphabetic characters.';
    }
    if (empty($last_name) || !preg_match('/^[A-Za-z]{1,20}$/', $last_name)) {
        $errors['lname'] = 'Last name must be 1-20 alphabetic characters.';
    }

    // Date of birth: accept yyyy-mm-dd (from date input) or dd/mm/yyyy, store as dd/mm/yyyy
    $dob_input = $dob;
    $dob_converted = '';
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob_input)) {
        $d = DateTime::createFromFormat('Y-m-d', $dob_input);
        if ($d && $d->format('Y-m-d') === $dob_input) {
            $dob_converted = $d->format('d/m/Y');
        } else {
            $errors['dob'] = 'Date of birth is not a valid date.';
        }
    } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dob_input)) {
        $d = DateTime::createFromFormat('d/m/Y', $dob_input);
        if ($d && $d->format('d/m/Y') === $dob_input) {
            $dob_converted = $dob_input;
        } else {
            $errors['dob'] = 'Date of birth is not a valid date.';
        }
    } else {
        $errors['dob'] = 'Date of birth must be in dd/mm/yyyy format.';
    }

    // Gender: required and limited values
    $allowed_genders = ['Male', 'Female', 'Other'];
    if (empty($gender) || !in_array($gender, $allowed_genders)) {
        $errors['gender'] = 'Gender is required.';
    }

    // Street and suburb max lengths
    if (empty($street) || mb_strlen($street) > 40) {
        $errors['street'] = 'Street address is required and must be 40 characters or less.';
    }
    if (empty($suburb) || mb_strlen($suburb) > 40) {
        $errors['suburb'] = 'Suburb/Town is required and must be 40 characters or less.';
    }

    // State: must be one of the allowed codes
    $allowed_states = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
    if (empty($state) || !in_array($state, $allowed_states)) {
        $errors['state'] = 'State selection is required.';
    }

    // Postcode: exactly 4 digits and should match state (simple prefix check)
    if (!preg_match('/^\d{4}$/', $postcode)) {
        $errors['postcode'] = 'Postcode must be exactly 4 digits.';
    } else {
        $prefix = $postcode[0];
        $state_regex = [
            'VIC' => '/^(3|8)\d{3}$/',
            'NSW' => '/^(1|2)\d{3}$/',
            'QLD' => '/^4\d{3}$/',
            'NT'  => '/^0\d{3}$/',
            'WA'  => '/^6\d{3}$/',
            'SA'  => '/^5\d{3}$/',
            'TAS' => '/^7\d{3}$/',
            'ACT' => '/^2\d{3}$/'
        ];
        if (isset($state_regex[$state]) && !preg_match($state_regex[$state], $postcode)) {
            $errors['postcode'] = 'Postcode does not match the selected state.';
        }
    }

    // Email format
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }

    // Phone number: allow digits and spaces, 8-12 digits total
    $phone_digits = preg_replace('/\s+/', '', $phone);
    if (!preg_match('/^\d{8,12}$/', $phone_digits)) {
        $errors['phone'] = 'Phone number must contain 8 to 12 digits (spaces allowed).';
    }

    // Skills: required at least one
    if (!is_array($skills) || count($skills) === 0) {
        $errors['skills'] = 'At least one technical skill must be selected.';
    } else {
        // validate values against expected list (simple whitelist)
        $allowed_skills = ['HTML', 'CSS', 'JavaScript', 'Python'];
        foreach ($skills as $s) {
            if (!in_array($s, $allowed_skills)) {
                $errors['skills'] = 'Invalid skill selection.';
                break;
            }
        }
    }
    // If there are validation errors, store them and the old values in session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = [
            'jobref' => $job_ref,
            'fname' => $first_name,
            'lname' => $last_name,
            'dob' => $dob, // original input (could be yyyy-mm-dd or dd/mm/yyyy)
            'gender' => $gender,
            'street' => $street,
            'suburb' => $suburb,
            'state' => $state,
            'postcode' => $postcode,
            'email' => $email,
            'phone' => $phone,
            'skills' => $skills,
            'others' => $other_skills
        ];
        $conn->close();
        header('Location: apply.php');
        exit();
    }

    // Prepare sanitized/normalized values for insertion
    $dob = $dob_converted; // store as dd/mm/yyyy

    // Map skills array to skill1-4 columns (up to 4 skills)
    $skill1 = isset($skills[0]) ? $skills[0] : NULL;
    $skill2 = isset($skills[1]) ? $skills[1] : NULL;
    $skill3 = isset($skills[2]) ? $skills[2] : NULL;
    $skill4 = isset($skills[3]) ? $skills[3] : NULL;
    $status = 'New';

    // Chuẩn bị SQL
    $sql = "INSERT INTO eoi 
        (job_ref, first_name, last_name, dob, gender, street, suburb, state, postcode, email, phone, skill1, skill2, skill3, skill4, other_skills, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssssssssss",
        $job_ref,
        $first_name,
        $last_name,
        $dob,
        $gender,
        $street,
        $suburb,
        $state,
        $postcode,
        $email,
        $phone,
        $skill1,
        $skill2,
        $skill3,
        $skill4,
        $other_skills,
        $status
    );

    // Thực thi
    if ($stmt->execute()) {
        // Chuyển hướng sang trang quản lý sau khi nộp
        header("Location: manage.php");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Đóng statement
    $stmt->close();
}

// Đóng kết nối
$conn->close();
