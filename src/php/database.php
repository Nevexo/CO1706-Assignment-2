<?php
// EcksMusic Database Setup
// Cameron Paul Fleming - 2021

// This file starts a connection to the MySQL database using PDO, this file is used by
// almost all other php files, the connection tears down automatically when PHP finishes rendering a page.

require_once 'vars.php';

// PDO Data source name for MySQL server as configured in vars.php
$DATABASE_DSN = "mysql:host=" . DATABASE_HOST . ";dbname=" . DATABASE_SCHEMA_NAME;

// Establish connection to database
$pdo = new PDO($DATABASE_DSN, DATABASE_USERNAME, DATABASE_PASSWORD);
