<?php
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
            Type::Email => '*Not a valid email',
            Type::Name => '*Must only contain letters',
            Type::PhoneNumber => '*Not a valid phone number',
            Type::NumberStr => '',
            Type::Password => '',
        };
    }
    public static function checkValid($val, Type $type)
    {
        switch ($type) 
        {
            case Type::Name:
                return preg_match("/^[a-zA-Z-' ]*$/", $val);

            case Type::Email:
                var_dump($_SESSION['user']);
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
}