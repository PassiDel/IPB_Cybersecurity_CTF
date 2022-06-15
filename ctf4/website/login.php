<?php
session_start();
require 'config/config.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = mysqli_real_escape_string($connect,htmlspecialchars($_POST['email']));
    $pass = mysqli_real_escape_string($connect,htmlspecialchars($_POST['password']));
    $check = $connect->query("select * from users where email='$email' and password='$pass' and id=1");
    if($check->num_rows){
        $_SESSION['userid'] = 1;
        header("location:dashboard.php");
        die();
    }

}
?>

<link rel="stylesheet" href="style/login.css">
<head>
    <script src="https://kit.fontawesome.com/fe909495a1.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Project_1.css">
    <title>Home</title>
</head>

<body>

<div class="container">
    <h1>👋 Welcome</h1>
    <!-- <a href="file:///C:/Users/SAURABH%20SINGH/Desktop/HTML5/PROJECTS/Project%201/Project_1.html"><h1>Sign In</h1></a> -->
    <!-- <a href="file:///C:/Users/SAURABH%20SINGH/Desktop/HTML5/PROJECTS/Project%201/P2.html">  <h1>Log In</h1></a> -->
    <form action="" method="post">
    <div class="box">
        <i  class="fas fa-envelope"></i>
        <input type="email" name="email" id="email"  placeholder="Enter Your Email" required>
    </div>
    <div class="box">
        <i  class="fas fa-key"></i>
        <input type="password" name="password" id="password" placeholder="Enter Your Password" required>
    </div>
        <button id="btn" name="button">Login</button>
    </form>


</div>

</body>