<?php
require_once __DIR__ . '/app/config.php';

// Destroy the session and redirect to login page
session_unset();
session_destroy();
redirect('login.php');
