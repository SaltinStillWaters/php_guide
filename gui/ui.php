<form method="post">
    <input type="text" name="item_name" placeholder="Enter Name: ">
    <br>

    <input type="text" name="item_price" placeholder="Enter Price: ">
    <br>

    <input type="radio" name="item_status" value="1">Available
    <input type="radio" name="item_status" value="0">Unavailable
    <br><br>

    <input type="submit" name="submit" value="submit">
</form>

<?php

if (isset($_POST["submit"])) 
{
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $item_status = $_POST['item_status'];

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
                VALUES('$item_name', '$item_price', $item_status);";

    $result = mysqli_query($connection, $sql);
    if ($result)
    {
        echo "item inserted";
    }
    else
    {
        echo "error in insertion: " . mysqli_error($connection);
    }

    mysqli_close($connection);


    unset($_POST["submit"]);
}


?>