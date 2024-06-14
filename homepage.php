<?php
@session_start();

require_once('page_controller.php');

PageController::init(false);

var_dump($_SESSION);
echo 'HOMEPAGE';