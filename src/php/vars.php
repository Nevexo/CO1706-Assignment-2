<?php
// Global variables for all scripts
// Cameron Paul Fleming - 2021

// Database Configuration
define("DATABASE_HOST",        'sql');
define("DATABASE_USERNAME",    'root');
define("DATABASE_PASSWORD",    'password');
define("DATABASE_SCHEMA_NAME", 'musicstream');

// Authentication Configuration
define("PASSWORD_MIN_LENGTH", 3);
define("ENABLE_REGISTRATION", true);

// Pagination Settings
define("PAGINATION_PAGE_TRACKS", 12); // Number of tracks to return per page of the paginator.