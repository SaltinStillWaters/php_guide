<?php 
if (!session_id()) session_start();


require_once('backend/input.php');
require_once('backend/type.php');   
require_once('page_controller.php');

PageController::init(true);
var_dump($_SESSION['pages']);
?>


<body>

<?php
Input::startSession();
if (isset($_POST['submit'])) 
{
    Input::cleanUp();   

    if (!Input::hasError())
    {
        var_dump($_SESSION['pages']);
        PageController::setCanAccess(false, 'welcome.php');
        header("Location: " . 'welcome.php');
    }
}

if (isset($_POST['refresh'])) 
{
    unset($_SESSION['user']);
    unset($_SESSION['pages']);
}

?>

<form method = 'post'>    
    <?php 
        Input::input('name', Type::Name, 'Name:', required:true, placeholder:'e.g. Shandong'); 
        Input::input('email', Type::Email, 'Email:', required:true, placeholder:'e.g. ss@gmail.com');
    ?>

    <input type="submit" name="submit" value="Submit">
    <input type="submit" name="refresh" value="Refresh">
</form>

</body>