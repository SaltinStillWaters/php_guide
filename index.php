<?php 
require_once('backend/input.php');
require_once('backend/type.php');   
?>


<body>

<?php
Input::startSession();
if (isset($_POST['submit'])) 
{
    Input::cleanUp();
}

if (isset($_POST['refresh'])) 
{
    unset($_SESSION['user']);
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