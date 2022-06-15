<?php

require "config/config.php";
$info = $connect->query("select * from users where id=1")->fetch_assoc();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = mysqli_real_escape_string($connect, $_POST["fname"]);
    $email = mysqli_real_escape_string($connect, $_POST["email"]);
    $address = mysqli_real_escape_string($connect, $_POST["address"]);
    $mobile = $_POST["mobile"];
    $connect->query("update users set username='{$fname}',email='{$email}',address='{$address}',contact_number='{$mobile}' where id=1");
    header("location:dashboard.php");
    die;
?>
}
<link href="style/dashboard.css"rel="stylesheet"><!doctypehtml><html lang="en"><head><title>Profiler Name || Krishivalahs</title><link href="/DevanagariBrahmi/logo.png"rel="shortcut icon"><meta content="width=device-width,initial-scale=1,user-scalable=no"name="viewport"><meta charset="UTF-8"><meta content="IE=edge,chrome=1"http-equiv="X-UA-Compatible"><meta content="Team Bboysdreamsfell"name="author"><meta content=""name="description"><meta content=""name="keywords"><meta content="en_US"property="og:locale"><meta content=""property="og:url"><meta content="Profiler Name || Krishivalahs"property="og:site_name"><link href="css/style.css"rel="stylesheet"><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script><link href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css"rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"rel="stylesheet"><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"rel="stylesheet"><link href="https://fonts.gstatic.com"rel="preconnect"><link href="https://fonts.googleapis.com/css2?family=Odibee+Sans&family=Oswald:wght@300;400&family=Ubuntu:wght@700&display=swap"rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Pattaya&display=swap"rel="stylesheet"><body><a class="btn"href="logout.php"style="margin:10px;width:100px">Logout</a><div class="container"><table><tr><td><section><label for="fileToUpload"></label> <img alt="Avatar"id="blah"src="https://i.ibb.co/yNGW4gg/avatar.png"></section><h1><?php 
echo $info["username"];
?>
</h1><h3>Web Designer & Developer</h3></td><td><ul><form action=""method="post"><li><b>Full name</b> <input id="fname"maxlength="100"name="fname"required value="<?php 
echo $info["username"];
?>
"> <i class="fa fa-edit"id="edit1"onclick='document.getElementById("fname").style.pointerEvents="auto",document.getElementById("fname").focus(),this.style.display="none",document.getElementById("check1").style.display="inline-block"'></i> <i class="fa fa-check"id="check1"onclick='document.getElementById("edit1").style.display="inline-block",this.style.display="none",document.getElementById("fname").style.pointerEvents="none"'style="display:none"></i></li><li><b>Email</b> <input id="email"maxlength="150"name="email"required value="<?php 
echo $info["email"];
?>
"type="email"></li><li><b>Contact number</b> <input id="mobile"maxlength="10"name="mobile"required value="<?php 
echo $info["contact_number"];
?>
                    "type="tel"> <i class="fa fa-edit"id="edit2"onclick='document.getElementById("mobile").style.pointerEvents="auto",document.getElementById("mobile").focus(),this.style.display="none",document.getElementById("check2").style.display="inline-block"'></i> <i class="fa fa-check"id="check2"onclick='document.getElementById("edit2").style.display="inline-block",document.getElementById("mobile").style.pointerEvents="none",this.style.display="none"'style="display:none"></i></li><li><b>Address</b> <input id="address"maxlength="250"name="address"required value="<?php 
echo $info["address"];
?>
"> <i class="fa fa-edit"id="edit3"onclick='document.getElementById("address").style.pointerEvents="auto",document.getElementById("address").focus(),this.style.display="none",document.getElementById("check3").style.display="inline-block"'></i> <i class="fa fa-check"id="check3"onclick='document.getElementById("edit3").style.display="inline-block",document.getElementById("address").style.pointerEvents="none",this.style.display="none"'style="display:none"></i></li><button class="btn"style="width:100px">SUBMIT</button></form></ul></td></tr></table></div><script src="js/custom.js"></script><script>function editdetails2(){}var i,close=document.getElementsByClassName("closebtn");for(i=0;i<close.length;i++)close[i].onclick=function(){var e=this.parentElement;e.style.opacity="0",setTimeout(function(){e.style.display="none"},600)}</script></body></html>
