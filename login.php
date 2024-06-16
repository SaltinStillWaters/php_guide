<?php
session_start();
require_once('utils/type.php');
require_once('utils/form.php');
require_once('utils/page_controller.php');
require_once('database.php');

Form::init();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (isset($_POST['submit']))
    {
        Form::updateContents();
        Form::updateErrors();

        if (!Form::hasError())
        {
            $conn = Database::establishConnection();
            $ids = ['name', 'number'];
            Form::insertToDB('user', $ids, [1, 1], $conn);

            Form::clearSession();
            
            PageController::setCanAccess(true, 'homepage.php');
            header("Location: homepage.php");
            exit();
        }
    }
    
    if (isset($_POST['refresh']))
    {
        $_SESSION = [];
    }
}
?>

<form method="post">
    <?php
    Form::input('name', Type::$Name, 'Name: ', 'e.g. Mychal', true);
    
    Form::input('number', Type::$PhoneNumber, 'Phone Number: ', 'e.g. 09554813800', true);
    ?>
    <input type="submit" value="Submit" name="submit">
    <input type="submit" value="Refresh" name="refresh">
    
</form>

<?php
?>