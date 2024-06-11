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
    {
        return;
    }
    
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
            if (!str_contains($_SESSION['user'][$id]['error'], '*required<br>'))
                $_SESSION['user'][$id]['error'] .= '*required<br>';
        }
        else
        {
            $_SESSION['user'][$id]['error'] = str_replace('*required<br>', '', $_SESSION['user'][$id]['error']);
        }

        //wrong or invalid content
        $type = $_SESSION['user'][$id]['type'];

        if (!checkValid($_SESSION['user'][$id]['content'], $type))
        {
            if (!str_contains($_SESSION['user'][$id]['error'], Type::errMsg($type) . '<br>'))
                $_SESSION['user'][$id]['error'] .= Type::errMsg($type) . '<br>';
        }
        else
        {
            $_SESSION['user'][$id]['error'] = str_replace(Type::errMsg($type) . '<br>', '', $_SESSION['user'][$id]['error']);
        }
    }
}

enum Type
{
    case Name;
    case PhoneNumber;
    case NumberStr;
    case Password;
    case Email;

    public static function errMsg($type) : string
    {
        return match($type)
        {
            Type::Name => '*Must only contain letters',
            Type::PhoneNumber => '*Not a valid phone number',
            Type::Email => '*Not a valid email',
            Type::NumberStr => '',
            Type::Password => '',
        };
    }
}

function checkValid($val, Type $type)
{
    switch ($type) 
    {
        case Type::Name:
            return preg_match("/^[a-zA-Z-' ]*$/", $val);

        case Type::Email:
            return filter_var($val, FILTER_VALIDATE_EMAIL);

        case Type::PhoneNumber:
            return preg_match("/^[0][9][0-9]{9}$/", $val);
        
        case Type::NumberStr:
            //not yet implemented
            return true;
            
        case Type::Password:
            //not yet implemented
            return true;

        default:
            return true;
    }
}