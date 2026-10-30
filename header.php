<?php 
// Start the session at the very top so we can check if the user is logged in
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstonCV</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>

<nav>
    <div class="nav-container">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="dashboard.php">My Dashboard</a>
            <a href="contact.php">Contact Us</a>
        </div>

        <div class="nav-auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="welcome">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <a href="logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
                <a href="signup.php" class="btn">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">