<?php
// Checks if the admin has an active session. If not, redirects them to the login page.
// This file should be included at the top of all secure administration pages.

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}