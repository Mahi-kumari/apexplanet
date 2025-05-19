<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Update details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Profile pic upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "profile_pics";
        $file_name = basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);

        // Update with profile picture
        $sql_update = "UPDATE users SET name='$name', email='$email', profile_pic='$target_file' WHERE id=$user_id";
    } else {
        // Update without picture
        $sql_update = "UPDATE users SET name='$name', email='$email' WHERE id=$user_id";
    }

    if (mysqli_query($conn, $sql_update)) {
        header("Location: profile.php"); // Refresh to show new data
        exit();
    } else {
        echo "Error updating profile.";
    }
}
?>

<!-- HTML Part -->
<h2>User Profile</h2>
<form method="POST" enctype="multipart/form-data">
    <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="100"><br><br>

    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

    <label>Profile Picture:</label>
    <input type="file" name="profile_pic"><br><br>

    <button type="submit">Update Profile</button>
</form>
