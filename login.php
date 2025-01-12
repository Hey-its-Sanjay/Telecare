<?php

include 'databaseconnection.php';
session_start(); // Start the session to store session variables

$message = []; // Initialize an array to store messages

if (isset($_POST['submit'])) {
    // Get and sanitize the input from the login form
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Check if the user exists in the database
    $select_users = mysqli_query($connection, "SELECT * FROM `users` WHERE username = '$username' AND password = '$password'");

    if (mysqli_num_rows($select_users) > 0) {
        $row = mysqli_fetch_assoc($select_users);

        // Store user details in the session
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['user_id'] = $row['user_id']; // Assuming 'id' is the column for user ID in the database

        $message[] = "Login successful! Welcome, " . $row['username'] . ".";
         // Add success message
         header("Location: dashboard.php");
exit();

    } else {
        $message[] = "Login failed! Invalid username or password."; // Add failure message
    }
}

// If there are any messages, display them
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message"><span>' . $msg . '</span></div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <img src="logo.png" width="160px">
                </div>
                <nav>
                    <ul id="MenuItems">
                    <li><a href="home.php" class="active">Home</a></li>
                    <li><a href="services.php" class="active">Services</a></li>
                    <li><a href="help.php" class="active">Help</a></li>
                    <li><a href="aboutus.php" class="active">About Us</a></li>
                    
                </ul>
                </nav>
                <div class="right">
                    <a href="login.php"> <button class="btn" id="loginBtn">Log In</button></a>
                    <a href="register.php"> <button class="btn" id="signupBtn">Sign up</button></a>
                </div>
            </div>
        </div>
     </div>

     <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Log In</button>
                <p>don't have a account? <a href ="register.php"> Signup Now </a></p>
            </div>
        </form>
     </div>
</body>
</html>