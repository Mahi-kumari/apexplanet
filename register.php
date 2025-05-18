<?php
// Include database connection
include 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $age      = intval($_POST['age']);
    $gender   = $_POST['gender'];
    $phone    = htmlspecialchars(trim($_POST['phone']));
    $address  = htmlspecialchars(trim($_POST['address'])); 
    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
    }
    if ($age <= 0) {
        throw new Exception("Invalid age.");
    }
}

    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (strlen($name) > 100) {
        $errors[] = "Name cannot exceed 100 characters.";
    }

    // Validate Email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate Password
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Validate Age
    if (empty($age)) {
        $errors[] = "Age is required.";
    } elseif (!filter_var($age, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 120]])) {
        $errors[] = "Age must be a valid number between 1 and 120.";
    }

    // Validate Gender
    $allowed_genders = ['Male', 'Female', 'Other'];
    if (empty($gender)) {
        $errors[] = "Gender is required.";
    } elseif (!in_array($gender, $allowed_genders)) {
        $errors[] = "Invalid gender selected.";
    }

    // Validate Phone (adjust pattern as needed)
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    // Validate Address
    if (empty($address)) {
        $errors[] = "Address is required.";
    } elseif (strlen($address) > 255) {
        $errors[] = "Address cannot exceed 255 characters.";
    }
    if (count($errors) === 0) {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        catch (mysqli_sql_exception $e) {
            $message = "<p style='color:red;'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        } catch (Exception $e) {
            $message = "<p style='color:red;'>⚠️ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        } finally {
            if (isset($stmt)) $stmt->close();
            $conn->close();
        }
    // Insert into database
    $sql = "INSERT INTO users (name, email, password, age, gender, phone, address) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssisss", $name, $email, $password, $age, $gender, $phone, $address);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>User registered successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }   

    $stmt->close();
    $conn->close();
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="register.php">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Age:</label><br>
    <input type="number" name="age" required><br><br>

    <label>Gender:</label><br>
    <select name="gender" required>
        <option value="">Select</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Address:</label><br>
    <textarea name="address" required></textarea><br><br>

    <input type="submit" value="Register">
    </form>
    <script>
document.querySelector("form").addEventListener("submit", function(e) {
    // Get form values
    const name = this.name.value.trim();
    const email = this.email.value.trim();
    const password = this.password.value;
    const age = this.age.value;
    const gender = this.gender.value;
    const phone = this.phone.value.trim();
    const address = this.address.value.trim();

    // Basic validations
    if (!name) {
        alert("Please enter your name.");
        e.preventDefault();
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        e.preventDefault();
        return;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        e.preventDefault();
        return;
    }

    if (!age || age <= 0) {
        alert("Please enter a valid age.");
        e.preventDefault();
        return;
    }

    if (!gender) {
        alert("Please select your gender.");
        e.preventDefault();
        return;
    }

    const phoneRegex = /^[0-9]{10}$/; // Adjust pattern as per your phone format
    if (!phoneRegex.test(phone)) {
        alert("Please enter a valid 10-digit phone number.");
        e.preventDefault();
        return;
    }

    if (!address) {
        alert("Please enter your address.");
        e.preventDefault();
        return;
    }
});
</script>

</body>
</html>
