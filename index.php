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
    <input type="radio" id="male" name="gender" value="Male">
    <input type="radio" id="female" name="gender" value="Female">
    <input type="submit" value="submit">
</form>