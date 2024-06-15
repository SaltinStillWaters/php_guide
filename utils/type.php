<?php
/**
 * This class contains static members that specify ALL the available input types of the user
 * It also contains methods that validate and sanitize each input type
 * In other words, all code injection and XSS attacks will be prevented by this class, granted that all input is taken through form.php
 */
class Type
{
    public static $Name = 'Name';
    public static $PhoneNumber = 'PhoneNumber';
    public static $NumberStr = 'NumberStr';
    public static $Password = 'Password';
    public static $Email = 'Email';
    
    public static function errMsg($type) : string
    {
        return match($type)
        {
            self::$Email => '*Not a valid email',
            self::$Name => '*Must only contain letters',
            self::$PhoneNumber => '*Not a valid phone number',
            self::$NumberStr => '',
            self::$Password => '',
        };
    }
    public static function checkValid($val, string $type)
    {
        switch ($type) 
        {
            case self::$Name:
                return preg_match("/^[a-zA-Z-' ]*$/", $val);

            case self::$Email:
                return filter_var($val, FILTER_VALIDATE_EMAIL);

            case self::$PhoneNumber:
                return preg_match("/^[0][9][0-9]{9}$/", $val);
            
            case self::$NumberStr:
                //not yet implemented
                return true;
                
            case self::$Password:
                //not yet implemented
                return true;

            default:
                return true;
        }
    }
}