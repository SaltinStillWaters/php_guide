<?php
require_once('type.php');

/**
 * Handles all form text input.
 * Works together with type.php to validate input.
 * 
 * Instructions:
 * - include the file
 * - call init()
 * - use input() to take textual input
 * - use updateSession() BEFORE the form code
 * SESSION format:
 * $_SESSION[$SESSION_NAME] = [
 *  [id_1] => [content => 'content', error => 'error', type => 'type', required => 'required'],
 *  [id_2] => [content => 'content', error => 'error', type => 'type', required => 'required'],
 *  etc...
 * ]
 */
class Form
{
    /**
     * The key to access form session variables.
     * E.g.: $_SESSION[$SESSION_NAME];
     */
    private static $SESSION_NAME = 'user';
    
    /**
     * Called at the start before any functions from class Form is called.
     * Initialized $_SESSION[$SESSION_NAME] to an empty array if it is not already set.
     */
    public static function init()
    {
        if (!isset($_SESSION[self::$SESSION_NAME]))
        {
            $_SESSION[self::$SESSION_NAME] = [];
        }
    }
    

    public static function input($id, $type, $label='', $placeholder='', $required = false)
    {
        self::addSession($id, $type, $required);
    
        if (!empty($label))
        {
            echo "<label for='{$id}'> {$label} </label> <br>";
        }
    
        echo "<input type='text' id='{$id}' name='{$id}' value='{$_SESSION[self::$SESSION_NAME][$id]['content']}' placeholder='{$placeholder}'> <br>";
    
        if($required)
        {
            echo "<span style='color: red;'> {$_SESSION[self::$SESSION_NAME][$id]['error']} <br></span>";
        }
    
        echo "<br>";
    }

    public static function hasError() : bool
    {
        foreach ($_SESSION[self::$SESSION_NAME] as $id => $key)
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

    public static function updateContents()
    {
        foreach ($_POST as $id => $content)
        {
            if ($id == 'submit' || $id == 'refresh')
            {
                continue;
            }

            if (isset($_SESSION[self::$SESSION_NAME][$id]))
            {
                $_SESSION[self::$SESSION_NAME][$id]['content'] = $content;
            }
        }
    }
    
    public static function updateErrors()
    {
        foreach ($_SESSION[self::$SESSION_NAME] as $id => $key)
        {
            if ($id == 'submit' || $id == 'refresh')
            {
                continue;
            }

            //check for invalid input
            if (!Type::checkValid($key['content'], $key['type']))
            {
                if (!str_contains($key['error'], Type::errMsg($key['type']) . '<br>'))
                {
                    $key['error'] .= Type::errMsg($key['type']) . '<br>';
                }
            }
            else
            {
                $key['error'] = str_replace(Type::errMsg($key['type']) . '<br>', '', $key['error']);
            }
            
            
            //check for blank input
            if (!$key['required'])
            {
                continue;
            }
            
            if ($key['content'] === '')
            {
                if (!str_contains($key['error'], '*Required <br>'))
                {
                    $key['error'] .= '*Required <br>';
                }
            }
            else
            {
                $key['error'] = str_replace('*Required <br>', '', $key['error']);
            }

            //update session error
            $_SESSION[self::$SESSION_NAME][$id]['error'] = $key['error'];
        }
    }
    private static function addSession(string $id, string $type, bool $required=false)
    {
        if (isset($_SESSION[self::$SESSION_NAME][$id]))
        {
            return;
        }
        
        $_SESSION[self::$SESSION_NAME][$id] = ['content' => '', 'error' => '', 'type' => $type, 'required' => $required];
    }
}