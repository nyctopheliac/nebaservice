<?php
$host="localhost";
$user="root";
$password="";
$database="login";
$conn=mysqli_connect($host,$user,$password,$database);
if($conn->connect_error){
    echo "Failed to connect to MySQL: " . $conn->connect_error;
}
else{
    echo "Connected.";
}
?>