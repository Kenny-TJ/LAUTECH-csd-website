<?php
require_once dirname(__FILE__) . '/admin_config.php';
session_destroy();
header('Location: /lautech_csd/admin/index.php');
exit;