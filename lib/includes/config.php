<?php

/* Turn on error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
    error_reporting(-1); // -1 = on || 0 = off
} else {
    error_reporting(0); // -1 = on || 0 = off
}

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

function createTables() {
    try {
        $conn = new PDO("mysql:host=localhost:8889;dbname=mysimpleblog", DATABASE_USERNAME, DATABASE_PASSWORD);
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
}

createTables();


/* Autoloads classes using namespaces                       */
require_once "lib/website_project/website_project.inc.php";

use website_project\database\Database as DB;

$db = DB::getInstance();
$pdo = $db->getConnection();