<?php
@session_start();

require_once('utils/page_controller.php');

PageController::init(false);

var_dump($_SESSION);
echo 'HOMEPAGE';