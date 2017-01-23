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

/* Autoloads classes using namespaces                       */
require_once "lib/website_project/website_project.inc.php";

/* Function folder of important and useful functions */
include 'lib/functions/functions.inc.php';

use website_project\database\Database as DB;

// Check for a user in the session:
$user = (isset($_SESSION["user"])) ? $_SESSION["user"] : NULL;


createTables(); // Create database tables if necessary:

/*
 * Create a constant PDO connection.
 */
$db = DB::getInstance();
$pdo = $db->getConnection();
