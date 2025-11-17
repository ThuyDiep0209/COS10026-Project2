<?php
session_start();

// ===============================
// SECURITY: Chỉ cho Manager đã đăng nhập
// ===============================
if (!isset($_SESSION['manager_logged_in'])) {
    header("Location: manager_login.php");
    exit();
}

require_once "settings.php";


// ===============================
// XỬ LÝ FORM: CHANGE STATUS
// ===============================
if (isset($_POST['update_status'])) {
    $id = intval($_POST['eoi_id']);
    $new_status = $_POST['new_status'];

    $valid_status = ["New", "Current", "Final"];
    if (in_array($new_status, $valid_status)) {
        $stmt = $conn->prepare("UPDATE eoi SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $id);
        $stmt->execute();
        $stmt->close();
        $status_message = "Status updated successfully!";
    }
}


// ===============================
// XỬ LÝ FORM: DELETE ALL BY JOBREF
// ===============================
if (isset($_POST['delete_by_jobref'])) {
    $jobref = trim($_POST['jobref_delete']);

    $stmt = $conn->prepare("DELETE FROM eoi WHERE jobref = ?");
    $stmt->bind_param("s", $jobref);
    $stmt->execute();
    $stmt->close();

    $delete_message = "All EOIs with job reference <strong>$jobref</strong> have been deleted.";
}


// ===============================
// XỬ LÝ TÌM KIẾM – THEO RUBRIC
// ===============================

$conditions = [];
$params = [];
$types = "";

// Lọc theo JobRef
if (!empty($_GET['jobref'])) {
    $conditions[] = "jobref = ?";
    $params[] = $_GET['jobref'];
    $types .= "s";
}

// Lọc theo First Name
if (!empty($_GET['fname'])) {
    $conditions[] = "fname LIKE ?";
    $params[] = "%" . $_GET['fname'] . "%";
    $types .= "s";
}

// Lọc theo Last Name
if (!empty($_GET['lname'])) {
    $conditions[] = "lname LIKE ?";
    $params[] = "%" . $_GET['lname'] . "%";
    $types .= "s";
}

// Gộp điều kiện SQL
$where_sql = "";
if (!empty($conditions)) {
    $where_sql = "WHERE " . implode(" AND ", $conditions);
}

// Query cuối cùng
$sql = "SELECT * FROM eoi $where_sql ORDER BY submitted_at DESC";
$stmt = $conn->prepare($sql);

if (!empty($conditions)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage EOIs</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>

    <?php include 'header.inc'; ?>

    <main class="manage-container">
        <h1>Manage Expressions of Interest (EOIs)</h1>


        <!-- =============================== -->
        <!-- THÔNG BÁO HÀNH ĐỘNG -->
        <!-- =============================== -->
        <?php if (!empty($status_message)): ?>
        <p style="color: green;"><?= $status_message ?></p>
        <?php endif; ?>

        <?php if (!empty($delete_message)): ?>
        <p style="color: red;"><?= $delete_message ?></p>
        <?php endif; ?>


        <!-- =============================== -->
        <!-- FORM TÌM KIẾM THEO RUBRIC -->
        <!-- =============================== -->
        <section class="search-section">
            <h2>Search EOIs</h2>
            <form method="get" action="manage.php">

                <label>Job Reference:</label>
                <input type="text" name="jobref" placeholder="e.g., NA101"
                    value="<?= htmlspecialchars($_GET['jobref'] ?? '') ?>">

                <label>First Name:</label>
                <input type="text" name="fname" placeholder="First Name"
                    value="<?= htmlspecialchars($_GET['fname'] ?? '') ?>">

                <label>Last Name:</label>
                <input type="text" name="lname" placeholder="Last Name"
                    value="<?= htmlspecialchars($_GET['lname'] ?? '') ?>">

                <button type="submit">Search</button>
                <a href="manage.php" class="btn secondary">Reset</a>
            </form>
        </section>


        <!-- =============================== -->
        <!-- DELETE ALL BY JOBREF -->
        <!-- =============================== -->
        <section class="delete-section">
            <h2>Delete All EOIs by Job Reference</h2>
            <form method="post">
                <input type="text" name="jobref_delete" required placeholder="Enter Job Reference (e.g., BE204)">
                <button type="submit" name="delete_by_jobref" class="btn danger">Delete All</button>
            </form>
        </section>


        <!-- =============================== -->
        <!-- HIỂN THỊ BẢNG EOI -->
        <!-- =============================== -->
        <section class="list-section">
            <h2>EOI Records</h2>

            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>ID</th>
                    <th>Job Ref</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Actions</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['jobref']); ?></td>
                    <td><?= htmlspecialchars($row['fname'] . " " . $row['lname']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td><?= $row['submitted_at']; ?></td>

                    <td>
                        <!-- Update status form -->
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="eoi_id" value="<?= $row['id'] ?>">

                            <select name="new_status">
                                <option <?= $row['status']=="New"?"selected":"" ?>>New</option>
                                <option <?= $row['status']=="Current"?"selected":"" ?>>Current</option>
                                <option <?= $row['status']=="Final"?"selected":"" ?>>Final</option>
                            </select>

                            <button name="update_status">Update</button>
                        </form>
                    </td>

                </tr>
                <?php endwhile; ?>

            </table>
        </section>

    </main>

    <?php include 'footer.inc'; ?>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>