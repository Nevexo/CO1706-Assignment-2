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
define("ENABLE_REGISTRATION", true);    // Enable/disable registration systen

// Pagination Settings
define("PAGINATION_PAGE_TRACKS", 12);   // Number of tracks to return per page of the paginator.

// Recommendation System Settings
define("RECOMMENDATION_WEIGHTING_GENRE",  50);  // Weighting to apply to genres
define("RECOMMENDATION_WEIGHTING_ALBUM",  30);  // Weighting to apply to albums
define("RECOMMENDATION_WEIGHTING_ARTIST", 20);  // Weighting to apply to artists