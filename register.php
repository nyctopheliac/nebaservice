<?php
include 'connect.php';

if (isset($_POST['register'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validação de input do utilizador
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.')</script>";
    } else {
        // verifica se o email já existe
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $checkEmailResult = mysqli_query($conn, $checkEmail);

        if (mysqli_num_rows($checkEmailResult) > 0) {
            echo "<script>alert('Email already exists.')</script>";
        } else {
            // password hashing com bcrypt
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // inserção do utilizador na DB
            $sql = "INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $hashedPassword);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "<script>alert('Registration successful!')</script>";
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.')</script>";
    } else {
        // Check if email exists
        $checkLogin = "SELECT * FROM users WHERE email='$email'";
        $checkLoginResult = $conn->query($checkLogin);

        if (mysqli_num_rows($checkLoginResult) > 0) {
            $row = $checkLoginResult->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['email'] = $row['email'];
                header("Location: homepage.php");
                exit();
            } else {
                echo "<Script>alert('Login failed!')</Script>";
            }
        } else {
            echo "<Script>alert('Login failed!')</Script>";
        }
    }
}
?>
