<?php
include 'header.php';
include 'db.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// pulling ALL the user's CV data so we can display it properly
$stmt = $conn->prepare("SELECT * FROM cvs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// if the ID doesn't match anyone in the database, show a simple error page
if (!$user) {
    echo "<div class='container'><h2>CV not found.</h2><a href='index.php'>Back to Directory</a></div>";
    include 'footer.php';
    exit();
}
?>

<div class="cv-profile container">
    <!-- quick link to go back to the directory -->
    <a class="back-link" href="index.php">← Back to Home</a>
    
    <!-- showing the user's name and email safely -->
    <h1><?php echo htmlspecialchars($user['name']); ?>'s Profile</h1>
    <p>Contact: <?php echo htmlspecialchars($user['email']); ?></p>
    <hr>

    <div class="cv-section">
        <h3>Professional Profile</h3>
        <div class="cv-box">
            <!-- nl2br makes line breaks actually show up on the page -->
            <p><?php echo nl2br(htmlspecialchars($user['profile'] ?: 'No profile summary provided.')); ?></p>
        </div>
    </div>

    <div class="cv-section">
        <h3>Technical Skills</h3>
        <div class="cv-box">
            <!-- showing their main programming language, or a fallback if they haven't set one -->
            <p><strong>Primary Language:</strong> <?php echo htmlspecialchars($user['keyprogramming'] ?: 'Not specified'); ?></p>
        </div>
    </div>

    <div class="cv-section">
        <h3>Education</h3>
        <div class="cv-box">
            <p><?php echo nl2br(htmlspecialchars($user['education'] ?: 'Information not provided.')); ?></p>
        </div>
    </div>

    <div class="cv-section">
        <h3>Portfolio & Professional Links</h3>
        <div class="cv-box">
            <!-- if they added links, show them; otherwise show a placeholder -->
            <?php if ($user['URLlinks']): ?>
                <?php echo nl2br(htmlspecialchars($user['URLlinks'])); ?>
            <?php else: ?>
                <p>No links or portfolio information provided.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="contact-footer">
        <!-- little call-to-action section so people can contact the programmer -->
        <h3>Interested in working with <?php echo htmlspecialchars($user['name']); ?>?</h3>
        <p>Feel free to reach out via the email address below:</p>
        <a class="btn-email" href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="btn">
            Send an Email
        </a>
    </div>
</div>

<?php
// cleaning up the prepared statement and closing the DB connection
$stmt->close();
$conn->close();

// footer 
include 'footer.php'; 
?>
