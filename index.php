<?php 
function input($id, $type, $label='', $placeholder='', $required = false)
{
    addSession($id, $type, $required);

    if (!empty($label))
        echo "<label for='{$id}'> {$label} </label> <br>";

    echo "<input type='text' id='{$id}' name='{$id}' value='{$_SESSION['user'][$id]['content']}'> <br>";

    if($required)
        echo "<span style='color: red;'> {$_SESSION['user'][$id]['error']} <br></span>";

    echo "<br>";
}

function addSession(string $id, Type $type, bool $required=false)
{
    if (isset($_SESSION['user'][$id]))
        return;
    
    $_SESSION['user'][$id] = ['content' => '', 'error' => '', 'type' => $type, 'required' => $required];
}

function updateContents()
{
    foreach ($_POST as $id => $content)
    {
        if ($id == 'submit')
            continue;

        $_SESSION['user'][$id]['content'] = $content;
    }
}

enum Type
{
    case Name;
    case NumberInt;
    case NumberStr;
    case Password;
}
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) 
{
    updateContents();
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