<?php
session_start();

require_once('type.php');

class Form
{
    public static function init()
    {
        if (!isset($_SESSION['user']))
        {
            $_SESSION['user'] = array(
                /* FORMAT: 
                *   'id' => [content => 'content', error => 'error', type => 'type', required => 'required']
                *   'id2'...
                */
            );
        }
    }
    
    public static function input($id, $type, $label='', $placeholder='', $required = false)
    {
        self::addSession($id, $type, $required);
    
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
}