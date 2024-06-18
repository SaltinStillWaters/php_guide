<?php
session_start();
require_once('db/database.php');

$_SESSION = [];

$conn = Database::establishConnection();
$sql = 'create or replace table user (
        USER_ID int not null AUTO_INCREMENT,
        FIRST_NAME varchar(255), 
        MIDDLE_NAME varchar(255), 
        LAST_NAME varchar(255), 
        EMAIL varchar(255), 
        BIRTHDAY varchar(255), 
        UNIT_NUMBER varchar(5), 
        HOUSE_NUMBER varchar(5), 
        STREET varchar(50), 
        VILLAGE varchar(50), 
        SUBDIVISION varchar(100), 
        ZIPCODE varchar(10), 
        COUNTRY varchar(100), 
        MOBILE_NUMBER varchar(11), 
        GENDER varchar(6), 
        DEPENDENT_FIRST_NAME varchar(255), 
        DEPENDENT_MIDDLE_NAME varchar(255), 
        DEPENDENT_LAST_NAME varchar(255), 
        DEPENDENT_BIRTHDAY varchar(255),
        primary key(USER_ID )
        );';
        
mysqli_query($conn, $sql);

header('Location: login.php');
exit();
