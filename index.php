<?php 
include 'header.php'; 
include 'db.php'; 

// grabbing whatever the user typed into the search bar (if anything)
// if nothing was typed, default to an empty string so the page still loads normally
$search = isset($_GET['search']) ? $_GET['search'] : '';
$noResults = false;

// if the user actually searched for something, we run the search logic
if ($search !== "") {
    $stmt = $conn->prepare("SELECT id, name, email, keyprogramming FROM cvs WHERE name LIKE ? OR keyprogramming LIKE ?");
    
    // wrapping the search term in % so SQL can match partial text
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // if nothing matched the search, we show a message and then fall back to showing everyone
    if ($result->num_rows === 0) {
        $noResults = true;
        // fallback query to show all programmers so the page isn't empty
        $result = $conn->query("SELECT id, name, email, keyprogramming FROM cvs");
    }
} else {
    // if no search was done at all, just show the full directory
    $result = $conn->query("SELECT id, name, email, keyprogramming FROM cvs");
}
?>

<div class="content-area" class="directory-container">
    <h2>Programmer Directory</h2>

    <!-- search bar so users can filter by name or programming language -->
    <form action="index.php" method="GET" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="search" id="directory-search-input" placeholder="Search name or language..." 
               value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 10px;">
        <button type="submit" class="btn">Search</button>

        <!-- if a search is active, show a clear button to reset the page -->
        <?php if ($search): ?>
            <a href="index.php" class="clear-btn">Clear</a>
        <?php endif; ?>
    </form>

    <!-- message that appears only when the search found nothing -->
    <?php if ($noResults): ?>
        <p id="status-msg" style="color: #721c24; background-color: #f8d7da; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb; text-align: center;">
            No results found for "<?php echo htmlspecialchars($search); ?>". Showing all programmers.
        </p>
    <?php endif; ?>

    <!-- table that lists all programmers -->
    <table class="directory-table" style="width: 100%; border-collapse: collapse; background: white;">
        <thead>
            <tr style="background: #333; color: white;">
                <th style="padding: 12px; text-align: left;">Name</th>
                <th style="padding: 12px; text-align: left;">Primary Language</th>
                <th style="padding: 12px; text-align: left;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <!-- looping through each programmer and printing their info -->
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 12px;"><?php echo htmlspecialchars($row['name']); ?></td>

                        <!-- if keyprogramming is empty, show 'Not set' so the table doesn't look broken -->
                        <td style="padding: 12px;"><?php echo htmlspecialchars($row['keyprogramming'] ?: 'Not set'); ?></td>

                        <td style="padding: 12px;">
                            <!-- link to view the full CV, using the ID we selected earlier -->
                            <a href="view_cv.php?id=<?php echo $row['id']; ?>" class="btn-small" 
                               style="background: #007bff; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.9em;">
                               View Full CV
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- if the database is empty, show a friendly message -->
                <tr>
                    <td colspan="3" style="padding: 20px; text-align: center;">No programmers registered yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
// closing prepared statement if it exists (avoids warnings)
if (isset($stmt)) { $stmt->close(); }

// closing the database connection because we're done with it
$conn->close();
?>

<?php 
// footer for consistent layout across pages
include 'footer.php'; 
?>
