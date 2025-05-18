<?php
include 'db_connect.php';

// Handle update after form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id      = $_POST['id'];
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $age     = $_POST['age'];
    $gender  = $_POST['gender'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET name=?, email=?, age=?, gender=?, phone=?, address=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssi", $name, $email, $age, $gender, $phone, $address, $id);

    if ($stmt->execute()) {
        echo "<p style='color:green; text-align:center;'>User updated successfully.</p>";
        echo "<p style='text-align:center;'><a href='manage_users.php'>Back to User List</a></p>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Show form with existing data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<p style='color:red; text-align:center;'>User not found.</p>";
        exit;
    }
} else {
    echo "<p style='color:red; text-align:center;'>Invalid Request.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
<h2 style="text-align:center;">Edit User</h2>

<form method="post" action="edit_user.php" style="width: 400px; margin: 0 auto;">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

    <label>Age:</label><br>
    <input type="number" name="age" value="<?= $user['age'] ?>" required><br><br>

    <label>Gender:</label><br>
    <select name="gender" required>
        <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
    </select><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required><br><br>

    <label>Address:</label><br>
    <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea><br><br>

    <input type="submit" value="Update User">
</form>
</body>
</html>
