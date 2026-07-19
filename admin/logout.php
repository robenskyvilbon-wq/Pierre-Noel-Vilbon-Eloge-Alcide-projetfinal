<?php
// admin/logout.php - Dekoneksyon administratè
require_once '../auth.php';
deconnecterAdmin();
header('Location: login.php');
exit;
?>
