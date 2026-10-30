<?php 
include 'header.php'; 
include 'db.php';

// checking if the user is actually logged in, otherwise they shouldn't be able to see this page
if (!isset($_SESSION['user_id'])) {
    // sending them back to login because they don't have permission yet
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// this runs only when the form is submitted, so we don't update anything accidentally
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // grabbing all the form inputs the user typed in
    $lang = $_POST['keyprogramming'];
    $profile = $_POST['profile'];
    $education = $_POST['education'];
    $url = $_POST['URLlinks']; 

    // preparing the SQL update so the user can change their CV info
    // using prepared statements to avoid SQL injection
    $stmt = $conn->prepare("UPDATE cvs SET keyprogramming = ?, profile = ?, education = ?, URLlinks = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $lang, $profile, $education, $url, $user_id);

    // if the update works, show a success message, otherwise show an error
    if ($stmt->execute()) {
        $message = "<p id='status-msg' style='color:green; text-align:center;'>Profile updated successfully!</p>";
    } else {
        // this usually means something went wrong with the database
        $message = "<p id='status-msg' style='color:red; text-align:center;'>Error updating profile.</p>";
    }
    $stmt->close();
}

// we fetch the user's current data so the form can be pre-filled
$stmt = $conn->prepare("SELECT name, email, keyprogramming, profile, education, URLlinks FROM cvs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
// turning the result into an associative array so we can echo it into the form
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="form-container">
    <h2>My Programmer Dashboard</h2>
    <!-- showing the user's name safely so no weird characters break the page -->
    <p>Welcome back, <strong><?php echo htmlspecialchars($user_data['name']); ?></strong>!</p>
    
    <?php echo $message; ?>

    <!-- form sends data back to this same page -->
    <form action="dashboard.php" method="POST" onsubmit="return confirmUpdate()">
        <label>Email (Cannot be changed):</label>
        <!-- email is disabled because it's basically the user's identity in the system -->
        <input type="text" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>

        <label>Primary Programming Language:</label>
        <!-- pre-filling the input so the user doesn't have to retype everything -->
        <input type="text" name="keyprogramming" placeholder="e.g. PHP, Python, JavaScript" 
               value="<?php echo htmlspecialchars($user_data['keyprogramming'] ?? ''); ?>">

        <label>Professional Profile / Bio:</label>
        <!-- textarea for longer text, again pre-filled -->
        <textarea name="profile" rows="4" placeholder="Tell us about your experience..."><?php echo htmlspecialchars($user_data['profile'] ?? ''); ?></textarea>

        <label>Education & Qualifications:</label>
        <textarea name="education" rows="4" placeholder="e.g. BSc Computer Science, Aston University"><?php echo htmlspecialchars($user_data['education'] ?? ''); ?></textarea>

        <label>Links:</label>
        <!-- user can paste GitHub, LinkedIn, etc. -->
        <textarea name="URLlinks" rows="2" placeholder="Paste your GitHub or LinkedIn links here..."><?php echo htmlspecialchars($user_data['URLlinks'] ?? ''); ?></textarea>
        
        <button type="submit">Update My CV</button>

        <!-- link to preview the public CV, opens in new tab -->
        <a href="view_cv.php?id=<?php echo $user_id; ?>" class="btn-alt" target="_blank">
            Preview My Public CV
        </a>
    </form>
</div>

<?php 
// loading the footer so the page layout stays consistent
include 'footer.php'; 
?>
