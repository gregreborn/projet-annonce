<?php
session_start();  // Ensure the session is started
header('Content-Type: text/plain'); // Optional: display as plain text for clarity
echo "Session contents:\n\n";
print_r($_SESSION);
?>
