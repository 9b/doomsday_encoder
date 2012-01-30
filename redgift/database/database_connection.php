<?php

include("database_information.php");

//Connect to the database
global $link;
$link = mysqli_connect("$host", "$username", "$password");

//Check the connection
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//Select the database
mysqli_select_db($link,$db_name)or die("cannot select DB");
?>