<?php
// Start the session to fetch the current session and immediately remove it, the redirect to the home screen
session_start();
session_destroy();
header("Location: ../index.php");