<?php
@session_start();
require_once('page_controller.php');

PageController::init(true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    var_dump($_SESSION);
    PageController::setCanAccess(true, 'homepage.php');
    header('Location: homepage.php');
}
?>

<form method="post">
    <input type="submit" value="Submit">
</form>
