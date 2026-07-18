<?php
// user/logout.php - Dekoneksyon
require_once '../auth.php';
deconnecterUser();
header('Location: index.php');
exit;
?>
