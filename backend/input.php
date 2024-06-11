<?php
require_once('type.php');

class Input
{
    public static function hasError() : bool
    {
        foreach ($_SESSION['user'] as $id => $key)
        {
            if ($id == 'submit' || $id == 'refresh')
            {
                continue;
            }

            if ($key['error'] != '')
            {
                return true;
            }
        }
        
        return false;
    }

    public static function startSession()
    {
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
    }

    public static function cleanUp()
    {
        Input::updateContents();
        Input::updateErrors();
        header("Location: " . $_SERVER['REQUEST_URI']);
    }
    public static function input($id, $type, $label='', $placeholder='', $required = false)
    {
        Input::addSession($id, $type, $required);
    
        if (!empty($label))
            echo "<label for='{$id}'> {$label} </label> <br>";
    
        echo "<input type='text' id='{$id}' name='{$id}' value='{$_SESSION['user'][$id]['content']}' placeholder='{$placeholder}'> <br>";
    
        if($required)
            echo "<span style='color: red;'> {$_SESSION['user'][$id]['error']} <br></span>";
    
        echo "<br>";
    }

    public static function addSession(string $id, Type $type, bool $required=false)
    {
        if (isset($_SESSION['user'][$id]))
        {
            return;
        }
        
        $_SESSION['user'][$id] = ['content' => '', 'error' => '', 'type' => $type, 'required' => $required];
    }
    public static function updateContents()
    {
        foreach ($_POST as $id => $content)
        {
            if ($id == 'submit')
                continue;

            $_SESSION['user'][$id]['content'] = $content;
        }
    }

    public static function updateErrors()
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

            if (!Type::checkValid($_SESSION['user'][$id]['content'], $type))
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
}