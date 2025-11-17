<?php
session_start();
require_once 'settings.php'; // $conn = mysqli_connect(...);
// Manager phải login
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: manager_login.php");
    exit();
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// Message hiển thị khi redirect
$msg = '';
if (!empty($_GET['msg'])) {
    $map = [
        'deleted' => 'Record deleted successfully!',
        'status'  => 'Status updated successfully!',
        'error'   => 'An error occurred.'
    ];
    $msg = $map[$_GET['msg']] ?? htmlspecialchars($_GET['msg']);
}

// Xử lý tìm kiếm
$search = trim($_GET['search'] ?? '');
$where_sql = "";
$params = [];
$types = "";

if ($search !== "") {
    $where_sql = "WHERE lname LIKE ? OR jobref LIKE ?";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param];
    $types = "ss";
}

// Query EOI
$sql = "SELECT id, jobref, fname, lname, email, phone, status, submitted_at 
        FROM eoi 
        $where_sql 
        ORDER BY submitted_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) die("SQL Error: " . $conn->error);

if (!empty($params)) {
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
    <link rel="stylesheet" href="styles/manage.css">
</head>

<body>

    <?php include 'header.inc'; ?>

    <main>
        <h1>Manage EOIs</h1>

        <?php if ($msg): ?>
        <p style="color: green"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        <!-- Logout button -->
        <div class="logout-container">
            <form action="manager_logout.php" method="post" style="display:inline;">
                <button type="submit" class="logout">Logout</button>
            </form>
        </div>

        <!-- Search form -->
        <form method="get" action="manage.php" class="search-form">
            <input type="text" name="search" placeholder="Search by Last Name or Job Ref"
                value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
            <a href="manage.php">Reset</a>
        </form>



        <!-- Table -->
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Job Ref</th>
                <th>First</th>
                <th>Last</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['jobref']) ?></td>
                <td><?= htmlspecialchars($row['fname']) ?></td>
                <td><?= htmlspecialchars($row['lname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>

                <td>

                    <!-- View -->
                    <a href="view_eoi.php?id=<?= $row['id'] ?>">View</a>

                    <!-- Update status -->
                    <form action="update_status.php" method="post" style="display:inline-block;">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <select name="status">
                            <?php
                                foreach (['New', 'Current', 'Final'] as $s) {
                                    $sel = ($s === $row['status']) ? "selected" : "";
                                    echo "<option value='$s' $sel>$s</option>";
                                }
                                ?>
                        </select>
                        <button type="submit" class="update">Update</button>
                    </form>

                    <!-- Delete -->
                    <form action="delete_eoi.php" method="post" style="display:inline-block;"
                        onsubmit="return confirm('Delete this EOI?');">
                        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="delete">Delete</button>
                    </form>

                </td>
            </tr>
            <?php endwhile; ?>

        </table>

    </main>

    <?php include 'footer.inc'; ?>

</body>

</html>