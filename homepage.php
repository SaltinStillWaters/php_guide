<?php
@session_start();

require_once('utils/page_controller.php');
require_once('database.php');

PageController::init(false);

$conn = Database::establishConnection();
Database::displayTable($conn, 'user');  