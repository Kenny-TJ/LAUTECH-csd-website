<?php
// admin/admin_config.php

define('ADMIN_USERNAME', 'csd_admin');
define('ADMIN_PASSWORD', password_hash('admin123', PASSWORD_BCRYPT));
define('ADMIN_TITLE', 'CS Department — Admin Panel');
define('SITE_URL', '/lautech_csd');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /lautech_csd/admin/index.php');
        exit;
    }
}