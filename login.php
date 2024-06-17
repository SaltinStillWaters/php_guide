<?php
session_start();
require_once('utils/type.php');
require_once('utils/form.php');
require_once('utils/page_controller.php');
require_once('db/database.php');
require_once('misc/misc.php');
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<?php
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
            //Form::insertToDB('user', $ids, [1, 1], $conn);

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
<div class="container">

    <div class="row">
    <?php
    Form::inputText('firstName', Type::$Name, 'First Name: ', 'e.g. Mychal', true);
    Form::inputText('middleName', Type::$Name, 'Middle Name: ', 'e.g. Bacus');
    Form::inputText('lastName', Type::$Name, 'Last Name: ', 'e.g. Pejana', true);
    ?>
    </div>
    
    <div class="row">
    <?php
    Form::inputText('email', Type::$Email, 'Email: ', 'e.g. MychalPejana@gmail.com', true);
    Form::inputDate('birthday', 'Birthday:', true);
    ?>
    </div>

    <div class="row">
    <?php
    Form::inputText('unitNumber', Type::$NumberStr, 'Unit No.: ', 'e.g. 99', false, '2');
    Form::inputText('houseNumber', Type::$NumberStr, 'House No.: ', 'e.g. 149', true, '2');
    Form::inputText('street', Type::$Text, 'Street: ', 'e.g. Panacea St.', true);
    Form::inputText('village', Type::$Text, 'Village: ', 'e.g. Strife Vill.', true);
    ?>
    </div>

    <div class="row">
    <?php
    Form::inputText('subdivision', Type::$Text, 'Subdivision: ', 'e.g. Goryeo', true);
    Form::inputText('zipCode', Type::$NumberStr, 'Zip Code: ', 'e.g. 1608', true);
    Form::inputDropDown('country', Misc::$countries, 'Country: ', true);
    ?>
    </div>

    <div class="row">
    <?php
    Form::inputText('mobileNumber', Type::$PhoneNumber, 'Mobile Number: ', 'e.g. 09554813900', true);
    Form::inputRadio('gender', ['male', 'female'], 'Gender: ', true);
    ?>
    </div>


    Dependent:

    <div class="row">
    <?php
    Form::inputText('dFirstName', Type::$Name, 'First Name: ', 'e.g. Mychal', true);
    Form::inputText('dMiddleName', Type::$Name, 'Middle Name: ', 'e.g. Bacus');
    Form::inputText('dLastName', Type::$Name, 'Last Name: ', 'e.g. Pejana', true);
    Form::inputDate('birthday', 'Birthday:', true);
    ?>
    </div>

    <div class="row">
    <?php
    Form::inputCheckBox('agree', '', true);
    ?>
    </div>

    <input type="submit" value="Submit" name="submit">
    <input type="submit" value="Refresh" name="refresh">

</div>
</form>

<?php
?>