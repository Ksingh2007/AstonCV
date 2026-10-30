<?php 
include 'header.php'; 
include 'db.php';

//Process the login when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared Statement to find the user by email
    // This prevents SQL Injection by not putting the $email variable directly in the query
    $stmt = $conn->prepare("SELECT id, name, password FROM cvs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with that email exists
    if ($row = $result->fetch_assoc()) {
        
        // password_verify() knows how to compare a plain string to a hash
        if (password_verify($password, $row['password'])) {
            
            //bStore user info in the SESSION
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            
            // Redirect them to the dashboard
            header("Location: dashboard.php");
            exit();
            
        } else {
            echo "<p style='color:red; text-align:center;'>Invalid password.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>No account found with that email.</p>";
    }
    $stmt->close();
}
?>

<div class="form-container">
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" id="loginPass" name="password" required>
        <input type="checkbox" onclick="togglePassword('loginPass')"> Show Password

        <button type="submit">Login</button>
    </form>
</div>
<?php 
// Load the Footer
include 'footer.php'; 
?>