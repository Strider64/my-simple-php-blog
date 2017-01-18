<?php
include 'connect/mySimpleDBconnect.php';
/*
 * Pepster's Place 
 * A Website Design & Development Company
 * President John R Pepp
 */

date_default_timezone_set("America/Detroit"); // Set Default Timezone:

$seconds = 60;
$minutes = 60;
$hours = 24;
$days = 14;
session_set_cookie_params($seconds * $minutes * $hours * $days, "/", "", true, true);
session_start();

// Check for a user in the session:
$user = (isset($_SESSION["user"])) ? $_SESSION["user"] : NULL;

try {
    $conn = new PDO("mysql:host=localhost:8889;dbname=mysimpleblog", , );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $table1 = "CREATE TABLE IF NOT EXISTS mysimpleblog (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                userid INT(11) NOT NULL,
                name VARCHAR(60) NOT NULL,
                title VARCHAR(60) NOT NULL,
                message TEXT NOT NULL,
                dateCreated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00')";
    $conn->exec($table1);
    $use = 'use mysimpleblog';
    $conn->exec($use);
    $table2 = "CREATE TABLE IF NOT EXISTS mysimpleregistration (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(60) NOT NULL,
                email VARCHAR(120) NOT NULL,
                password VARCHAR(255) NOT NULL,
                dateCreated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00')";
    $conn->exec($table2);
    $conn = NULL;
    
} catch (PDOException $e) {
    echo "Something went wrong" . $e->getMessage();
}

$db_options = array(
    /* important! use actual prepared statements (default: emulate prepared statements) */
    PDO::ATTR_EMULATE_PREPARES => false
    /* throw exceptions on errors (default: stay silent) */
    , PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    /* fetch associative arrays (default: mixed arrays)    */
    , PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$pdo = new PDO('mysql:host=' . DATABASE_HOST . ';dbname=' . DATABASE_NAME . ';charset=utf8', DATABASE_USERNAME, DATABASE_PASSWORD, $db_options);
