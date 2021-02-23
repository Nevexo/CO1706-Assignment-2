<?php
// Database setup/teardown code
require_once 'vars.php';

// PDO Data source name for MySQL server as configured in vars.php
$DATABASE_DSN = "mysql:host=" . $DATABASE_HOST . ";dbname=" . $DATABASE_SCHEMA_NAME;

// Establish connection to database
$pdo = new PDO($DATABASE_DSN, $DATABASE_USERNAME, $DATABASE_PASSWORD);
