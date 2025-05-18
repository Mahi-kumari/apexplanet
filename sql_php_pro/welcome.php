<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "<h1>Welcome, " . htmlspecialchars($_SESSION['user_name']) . "!</h1>";
echo '<p><a href="logout.php">Logout</a></p>';
?>
