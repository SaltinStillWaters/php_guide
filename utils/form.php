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
    public static $SESSION_NAME = 'user';
    
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

    /**
     * Generates an input textbox
     * 
     * @param string $id the id to assign to the input
     * @param string $type the type of input as defined by type.php
     * @param string $label the text to display above the textbox
     * @param string $placeholder the placeholder to display
     * @param bool $required specifies if input is required
     */
    public static function inputText(string $id, string $type, string $label='', string $placeholder='', bool $required = false)
    {
        self::addSession($id, $type, $required);
    
        echo "<div class='col'>";

        if (!empty($label))
        {
            echo "<label for='{$id}'> {$label} </label> <br>";
        }
    
        echo "<input class='form-control' type='text' id='{$id}' name='{$id}' value='{$_SESSION[self::$SESSION_NAME][$id]['content']}' placeholder='{$placeholder}'>";
    
        if($required)
        {
            echo "<span style='color: red;'> {$_SESSION[self::$SESSION_NAME][$id]['error']} <br></span>";
        }

        echo "</div>";
    }

    public static function inputDate(string $id, string $label='', bool $required = false)
    {
        self::addSession($id, Type::$Date, $required);
        echo "<div class='col'>";

        if (!empty($label))
        {
            echo "<label for='{$id}'> {$label} </label> <br>";
        }
    
        echo "<input class='form-control' type='date' id='{$id}' name='{$id}' value='{$_SESSION[self::$SESSION_NAME][$id]['content']}'>";
    
        if($required)
        {
            echo "<span style='color: red;'> {$_SESSION[self::$SESSION_NAME][$id]['error']} <br></span>";
        }

        echo "</div>";
    }
    public static function insertToDB($tableName, $ids, $isStrings, $conn)
    {
        if (self::hasError())
        {
            echo '<br>User input has errors<br>';
            return false;
        }

        $sql = "insert into $tableName 
                values(0, ";

        for ($x = 0; $x < count($ids); ++$x)
        {
            $temp = $_SESSION[self::$SESSION_NAME][$ids[$x]]['content'];
            if ($isStrings[$x])
            {
                $temp = "'$temp'";
            }

            $sql .= $temp;
            if ($x + 1 < count($ids))
            {
                $sql .= ", ";
            }
        }
        $sql .= ");";

        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);

        return $result;
    }
    
    /**
     * Iterates through the inputs and checks if there are errors.
     * 
     * @return bool returns true if there are errors, otherwise, returns false
     */
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

    /**
     * Updates the contents in the the appropriate session variables.
     * Must be called before input()
     */
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
    
    /**
     * Updates the error in the appropriate session variables
     * Must be called before input()
     */
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
    /**
     * Unsets the Session variables associated to the input
     */
    public static function clearSession()
    {
        unset($_SESSION[self::$SESSION_NAME]);
    }

    /**
     * Used by inptu() to add a session variable if it is not yet set
     */
    private static function addSession(string $id, string $type, bool $required=false)
    {
        if (isset($_SESSION[self::$SESSION_NAME][$id]))
        {
            return;
        }
        
        $_SESSION[self::$SESSION_NAME][$id] = ['content' => '', 'error' => '', 'type' => $type, 'required' => $required];
    }
}