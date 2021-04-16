<?php
// Global configuration constants for all scripts
// Cameron Paul Fleming - 2021

// Database Configuration
define("DATABASE_HOST",        'sql');           // Hostname for the MySQL server
define("DATABASE_USERNAME",    'root');          // Username to use when connecting to MySQL
define("DATABASE_PASSWORD",    'password');      // Password to use when connecting to MySQL
define("DATABASE_SCHEMA_NAME", 'musicstream');   // Name of database to use on MySQL

// Authentication Configuration
define("PASSWORD_MIN_LENGTH", 3);       // Minimum password length
define("ENABLE_REGISTRATION", true);    // Enable/disable registration system.

// Pagination Settings
define("PAGINATION_PAGE_TRACKS", 12);   // Number of tracks to return per page of the paginator.

// Recommendation System Settings
define("RECOMMENDATION_TRACK_COUNT", 20); // Number of random tracks to select for recommendations system.