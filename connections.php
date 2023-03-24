<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "ymca";

$conn = mysqli_connect($dbHost,$dbUser,$dbPassword,$dbName);

if(!$conn)
{
    die("failed to connect!!");
}
