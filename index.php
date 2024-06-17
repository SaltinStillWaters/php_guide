<?php
session_start();
//$_SESSION[] = [];

header('Location: login.php');
exit();

//TEST
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    var_dump($_POST);
}
?>

<form method="post">
    <input type="date" name="date">
    <input type="submit" value="submit">
</form>