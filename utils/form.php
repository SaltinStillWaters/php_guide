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
     * @param string $colSpan specifies how many columns input should take. Leave blank to make it flexible
     */
    public static function inputText(string $id, string $type, string $label='', string $placeholder='', bool $required = false, string $colSpan='')
    {
        self::addSession($id, $type, $required);
    
        echo empty($colSpan)    ?   "<div class='col'>" : 
                                    "<div class='col-$colSpan'>";
        echo empty($label)      ?   "" : 
                                    "<label for='{$id}'> {$label} </label> <br>";

        $type = $type === Type::$Date ? 'date' : 'text';

        echo "<input class='form-control' type='$type' id='$id' name='$id' value='{$_SESSION[self::$SESSION_NAME][$id]['content']}' placeholder='$placeholder'>";
    
        echo "<span style='color: red;'> {$_SESSION[self::$SESSION_NAME][$id]['error']} <br></span>";

        echo "</div>";
    }
    
    public static function inputRadio(string $id, array $options, string $label='', bool $required = false, string $colSpan='')
    {
        self::addSession($id, Type::$Text, $required);
        
        echo empty($colSpan)    ?   "<div class='col'>" : 
                                    "<div class='col-$colSpan'>";
        echo empty($label)      ?   "" : 
                                    "<label for='{$id}'> {$label} </label> <br>";
        
        $default = $_SESSION[self::$SESSION_NAME][$id]['content'];
        
        foreach ($options as $x)
        {
            echo "<input type='radio' name='$id' id='$x' value='$x'";
            echo $default === $x    ?   " checked" : 
                                        "";
            echo "><label for='$x'>" . strtoupper($x) . "</label> &nbsp;&nbsp;&nbsp;&nbsp;";
        }
        echo '<br>';

        echo "<span style='color: red;'> {$_SESSION[self::$SESSION_NAME][$id]['error']} <br></span>";

        echo "</div>";
    }
    public static function inputCheckBox(string $id, string $label='', bool $required = false)
    {
        self::addSession($id, Type::$Text, $required);

        $_SESSION[self::$SESSION_NAME][$id]['content'] = "";

        echo "<div class='col'>";

        echo empty($label)      ?   "" : 
                                    "<label for='{$id}'> {$label} </label> <br>";

        echo "<input type='checkbox' id='{$id}' name='{$id}' value='{$id}'>";
        echo "<label for='$id'>&nbsp;I agree to the terms and conditions</label><br>";
        
        echo "<span style='color: red;'> {$_SESSION[self::$SESSION_NAME][$id]['error']} <br></span>";

        echo "</div>";
    }

    public static function inputDropDown(string $id, array $options, string $label='', bool $required = false, string $colSpan='')
    {
        self::addSession($id, Type::$Text, $required);
        
        echo empty($colSpan)    ?   "<div class='col'>" : 
                                    "<div class='col-$colSpan'>";
        echo empty($label)      ?   "" : 
                                    "<label for='{$id}'> {$label} </label> <br>";
    
        echo "<select class='form-control' name='$id', id='$id'>";
        
        $default = $_SESSION[self::$SESSION_NAME][$id]['content'];
        
        foreach ($options as $x)
        {
            echo "<option value='$x'";

            echo $default === $x    ?   " selected" 
                                        : "";
            echo $x === ""          ?   " hidden disabled> --Select Country--" 
                                        : ">$x";

            echo "</option> <br>";
        }

        echo "</select>";

        echo "<span style='color: red;'> {$_SESSION[self::$SESSION_NAME][$id]['error']} <br></span>";

        echo "</div>";
    }
    public static function insertAllToDB($tableName, $notIncluded, $conn)
    {
        if (self::hasError())
        {
            echo '<br>User input has errors<br>';
            return false;
        }

        $sql = "insert into $tableName 
                values(0, ";

        foreach ($_SESSION[self::$SESSION_NAME] as $id => $val)
        {
            if (in_array($id, $notIncluded))
            {
                continue;
            }

            $sql .= "'{$val['content']}', ";
        }

        $sql = substr($sql, 0, -2);

        $sql .= ");";
        
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);

        return $result;
    }

    public static function insertToDB($tableName, $ids=[], $isStrings=[], $conn)
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
                $_SESSION[self::$SESSION_NAME][$id]['error'] = $key['error'];
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