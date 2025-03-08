<?php

include 'connect.php';

if (isset($_POST['register'])) {
    $firstName=$_POST['fName'];
    $lastName=$_POST['lName'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password=md5($password);

    $checkEmail="SELECT * FROM users WHERE email='$email'";
    $checkEmailResult=mysqli_query($conn, $checkEmail);

    if (mysqli_num_rows($checkEmailResult)>0) {
        echo "<script>alert('Email already exists!')</script>";
    } else {
        $sql="INSERT INTO users (firstName, lastName, email, password) VALUES('$firstName', '$lastName', '$email', '$password')";
        if(mysqli_query($conn, $sql)==true){
            echo "<script>alert('Registration successful!')</script>";
            echo "<script>window.location.href='index.php';</script>";
            exec('sudo Unused\createUserAndFolder.sh');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

if(isset($_POST['login'])){
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password=md5($password);

    $checkLogin="SELECT * FROM users WHERE email='$email' AND password='$password'";
    $checkLoginResult=$conn->query($checkLogin);

    if (mysqli_num_rows($checkLoginResult)>0) {
        session_start();
        $row=$checkLoginResult->fetch_assoc();
        $_SESSION['email']=$row['email'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "<Script>alert('Login failed!')</Script>";
    }
}


?>