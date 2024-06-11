<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "testDB";

$connection = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName);

if (mysqli_connect_errno()) 
{
    echo "mysql error: ". mysqli_connect_error();
    exit();
}

$sql = "INSERT INTO ITEM(ITEM_NAME, ITEM_PRICE, ITEM_STATUS)
            VALUES('MOUSE', '2500', 1);";

$result = 0; //mysqli_query($connection, $sql);
if ($result)
{
    echo "item inserted";
}
else
{
    echo "error in insertion: " . mysqli_error($connection);
}

mysqli_close($connection);