<?php
// Include DB connection
include 'db_connect.php';

// Check if user ID is passed in URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // Redirect back to manage_users after successful deletion
            header("Location: manage_users.php?msg=deleted");
            exit();
        } else {
            echo "Error deleting user.";
        }
        $stmt->close();
    } else {
        echo "Failed to prepare delete statement.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
