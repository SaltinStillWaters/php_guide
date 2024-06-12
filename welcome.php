<?php
if (!session_id()) session_start();


require_once('page_controller.php');
PageController::init(true);
var_dump($_SESSION['pages']);
echo 'Welcome';