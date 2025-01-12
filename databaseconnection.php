<?php 

$servername ="localhost";
$username ="root";
$password ="";
$database ="telemedicine";

$connection = new mysqli($servername, $username, $password, $database);
if($connection){
    echo"Database connected";
}