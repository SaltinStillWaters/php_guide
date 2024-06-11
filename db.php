<?php
function establishConnection($dbName, $dbHost='localhost', $dbUser='root', $dbPass='')
{
    $connection = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName);
    
    if (mysqli_connect_errno()) 
    {
        echo "mysql error: ". mysqli_connect_error();
        exit();
    }

    return $connection;
}