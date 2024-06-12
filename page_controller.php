<?php
class PageController
{ 
    private static $INITITAL_PAGE = 'index.php';
    
    public static function init($isAccessible)
    {
        if (!isset($_SESSION['pages']['PREV_PAGE']))
        {
            $_SESSION['pages']['PREV_PAGE'] = self::$INITITAL_PAGE;
        }
    
        PageController::addScript($isAccessible);
        PageController::pageGuard();
    }
    public static function setPrevPage($page)
    {
        $_SESSION['pages']['PREV_PAGE'] = $page;
    }
    public static function checkIfAccessible() : bool
    {
        $fileName = PageController::getFileName();

        if (!isset($_SESSION['pages'][$fileName]))
        {
            echo "$fileName is not defined in SESSION['pages'] <br>";
            return false;
        }
        
        return $_SESSION['pages'][$fileName];
    }

    public static function getFileName()
    {
        $fileName = $_SERVER['PHP_SELF'];
        $fileName =  explode('/', $fileName);

        return end($fileName);
    }


    public static function setCanAccess(bool $canAccess, string $page='')
    {
        if ($page === '')
        {
            $page = PageController::getFileName();
        }
        
        $_SESSION['pages'][$page] = $canAccess;   
    }

    private static function addScript(bool $isAccessible)
    {
        $fileName = PageController::getFileName();

        if (!isset($_SESSION['pages'][$fileName]))
        {
            $_SESSION['pages'][$fileName] = $isAccessible;
        }
    }

    private static function pageGuard()
    {
        if (!PageController::checkIfAccessible() && PageController::getFileName() != $_SESSION['pages']['PREV_PAGE'])
        {
            header('Location: ' . $_SESSION['pages']['PREV_PAGE']);
        }
    }
}