<?php

if (filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_URL) == "localhost") {
  define('DATABASE_HOST', 'localhost');
  define('DATABASE_NAME', 'mysimpleblog');
  define('DATABASE_USERNAME', 'username'); // Usaully root:
  define('DATABASE_PASSWORD', 'your password');
  define('DATABASE_DB', 'mysimpleblog');

} else {
    /* REMOTE SERVER CONSTANTS */
}
