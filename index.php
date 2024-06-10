<?php 
require_once('backend.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

<?php

session_start();
if (!isset($_SESSION['user']))
{
    $_SESSION['user'] = array(
        /* FORMAT: 
        *   'id' => [content => 'content', error => 'error', type => 'type', required => 'required']
        *   'id2'...
        *   'passed' => bool
        */
    'passed' => false
    );
}

if (isset($_POST['submit'])) 
{
    updateContents();
    updateErrors();
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
?>

<form method = 'post'>    
    <?php input('name', Type::Name, 'Name:', required:true); ?>

    <input type="submit" name="submit" value="Submit">
</form>
</body>
</html> 