<?php
// Database Configuration File
// Replace the values below with your local configuration

// Definition of access credentials
$db_host = 'localhost';         // Usually 'localhost' or '127.0.0.1'
$db_name = 'mercearia_db';      // The name of the database we created
$db_user = 'root';              // Your database user (the default for XAMPP/MAMP is 'root')
$db_pass = '';                  // Your database password (the default for XAMPP/MAMP is empty)
$charset = 'utf8mb4';           // Character set

// DSN (Data Source Name) - defines the connection
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

// Options for PDO (PHP Data Objects)
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws exceptions in case of error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Returns results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Uses native MySQL query preparations
];

try {
    // Creates a new instance of PDO for the connection
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // If the connection fails, terminates the script and shows an error message
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
