<?php
session_start();

if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) {
    header("Location: manager_login.php");
    exit();
}

require_once "settings.php";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

$eoi_id = intval($_GET['id'] ?? 0);
if ($eoi_id <= 0) die("Invalid EOI ID");

$sql = "SELECT * FROM eoi WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eoi_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) die("EOI not found");

$eoi = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>EOI Details</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>

    <?php include 'header.inc'; ?>

    <main class="view-container">
        <h1>EOI Details (ID: <?= $eoi['id'] ?>)</h1>

        <table class="view-table" border="0" cellpadding="6">
            <?php foreach ($eoi as $key => $val): ?>
            <tr>
                <th><?= htmlspecialchars(ucfirst($key)) ?></th>
                <td><?= nl2br(htmlspecialchars($val)) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <br>

        <!-- Update Status -->
        <form action="update_status.php" method="post" style="display:inline-block;">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="hidden" name="id" value="<?= $eoi['id'] ?>">

            <label>Status:
                <select name="status">
                    <?php
                foreach (['New', 'Current', 'Final'] as $s) {
                    $sel = ($s === $eoi['status']) ? "selected" : "";
                    echo "<option value='$s' $sel>$s</option>";
                }
                ?>
                </select>
            </label>

            <button type="submit">Update Status</button>
        </form>

        <!-- Delete -->
        <form action="delete_eoi.php" method="post" style="display:inline-block;"
            onsubmit="return confirm('Delete this EOI?');">
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <input type="hidden" name="id" value="<?= $eoi['id'] ?>">
            <button type="submit">Delete</button>
        </form>

        <p><a href="manage.php">← Back to Manage</a></p>
    </main>

    <?php include 'footer.inc'; ?>
</body>

</html>