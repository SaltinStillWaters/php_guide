<?php
function input($id, $type, $label='', $placeholder='', $required = false)
{
    addSession($id, $type, $required);

    if (!empty($label))
        echo "<label for='{$id}'> {$label} </label> <br>";

    echo "<input type='text' id='{$id}' name='{$id}' value='{$_SESSION['user'][$id]['content']}' placeholder='{$placeholder}'> <br>";

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

function updateErrors()
{
    foreach ($_SESSION['user'] as $id => $key)
    {
        if ($id == 'submit' || !$key['required'])
            continue;
        
        //blank content
        if (!$_SESSION['user'][$id]['content'])
        {
            $_SESSION['user'][$id]['error'] .= '*required';
        }
        else
        {
            $_SESSION['user'][$id]['error'] = str_replace('*required', '', $_SESSION['user'][$id]['error']);
        }


    }
}

enum Type
{
    case Name;
    case NumberInt;
    case NumberStr;
    case Password;
}