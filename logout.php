<?php
// We must start the session to be able to destroy it
session_start();
// Clear all the variables we saved (user_id, user_name, etc.)
session_unset();
// Destroy the session entirely on the server
session_destroy();
// Redirect the user to the home page (index.php)
header("Location: index.php?info=loggedout");
// Ensure no further code runs after the redirect
exit();
?>