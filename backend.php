<?php
function input($id, $type, $label='', $placeholder='', $required = false)
{
    if (!empty($label))
        echo "<label for='{$id}'> {$label} </label> <br>";

    session_start();

    echo "<input type='text' id='{$id}' name='{$id}' value='{$_SESSION['user']['content']}'placeholder='{$placeholder}'> <br>";

    if($required)
        echo "<span style='color: red;'> {$_SESSION['user']['error']} <br></span>";

    echo "<br>";
}