<?php 
include 'header.php'; 
include 'db.php';

$feedbackMessage = "";
$feedbackColor = "red";

// code runs only if the user clicks the "Register" button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $raw_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // VALIDATION GATES - Each gate checks a specific condition and sets feedback messages accordingly
    // GATE 0: Validate Name
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $feedbackMessage = "Error: Name must only contain letters and spaces.";
        $feedbackColor = "red";
    }
    // GATE 1: Check if passwords match
    if ($raw_password !== $confirm_password) {
        $feedbackMessage = "Error: Passwords do not match.";
        $feedbackColor = "red";
    } 
    // GATE 2: Check Password Strength
    elseif (!preg_match('@[A-Z]@', $raw_password) || 
            !preg_match('@[a-z]@', $raw_password) || 
            !preg_match('@[0-9]@', $raw_password) || 
            !preg_match('@[^A-Za-z0-9]@', $raw_password) || // Matches any symbol including dots
            strlen($raw_password) < 8) {
        $feedbackMessage = "Password must be 8+ chars with Uppercase, Lowercase, Number, and a Symbol (like . or !)";
        $feedbackColor = "red";
    } 
    // GATE 3: Database Insertion & Duplicate Check
    else {
        // Hash the password
        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

        try {
            // Prepared Statement
            $stmt = $conn->prepare("INSERT INTO cvs (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $feedbackMessage = "Account created successfully! You can now log in.";
                $feedbackColor = "green";
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // This catches the 'Duplicate entry' error for the email key
            if ($e->getCode() === 1062) {
                $feedbackMessage = "Error: This email is already registered.";
            } else {
                $feedbackMessage = "An unexpected error occurred. Please try again.";
            }
            $feedbackColor = "red";
        }
    }
}
?>

<div class="form-container">
    <h2>Create Your Account</h2>
    
    <?php if ($feedbackMessage): ?>
        <p id="status-msg" style="color: <?php echo $feedbackColor; ?>; text-align:center; font-weight: bold;">
            <?php echo $feedbackMessage; ?>
        </p>
    <?php endif; ?>

    <p id="error-message" style="color:red; text-align:center; font-weight: bold;"></p>
    
    <form action="signup.php" method="POST" onsubmit="return validateSignup()">
        <label>Full Name</label>
        <input type="text" name="name" pattern="^[a-zA-Z\s]+$" title="Name can only contain letters and spaces." required>

        <label>Email Address</label>
        <input type="email" id="email" name="email" required>

        <label>Password</label>
        <div style="position:relative;">
            <input type="password" id="pass1" name="password" required>
            <input type="checkbox" onclick="togglePassword('pass1')"> Show Password
        </div>

        <label>Confirm Password</label>
        <div style="position: relative;">
            <input type="password" id="pass2" name="confirm_password" required>
            <input type="checkbox" onclick="togglePassword('pass2')"> Show Password
        </div>

        <button type="submit">Register</button>
    </form>
</div>
<?php

// Load the Footer
include 'footer.php'; 
?>