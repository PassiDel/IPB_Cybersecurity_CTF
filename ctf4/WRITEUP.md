# CTF 04
> Capture The Flag.
 The flag is stored in the user.txt file.
* IP: 192.168.10.17.

First thing to do is an agressive scan to the ip using nmap command
## nmap

```bash 
$ nmap -sV -Pn -p- -A $IP
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-14 06:40 EDT
Nmap scan report for 192.168.10.17
Host is up (0.0043s latency).
Not shown: 65533 filtered tcp ports (no-response)
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.2p1 Ubuntu 4ubuntu0.3 (Ubuntu Linux; protocol 2.0)
| ssh-hostkey: 
|   3072 57:b1:f5:64:28:98:91:51:6d:70:76:6e:a5:52:43:5d (RSA)
|   256 cc:64:fd:7c:d8:5e:48:8a:28:98:91:b9:e4:1e:6d:a8 (ECDSA)
|_  256 9e:77:08:a4:52:9f:33:8d:96:19:ba:75:71:27:bd:60 (ED25519)
80/tcp open  http    Apache httpd 2.4.41 ((Ubuntu))
| http-cookie-flags: 
|   /: 
|     PHPSESSID: 
|_      httponly flag not set
|_http-title: DarkHole V2
| http-git: 
|   192.168.10.17:80/.git/
|     Git repository found!
|     Repository description: Unnamed repository; edit this file 'description' to name the...
|_    Last commit message: i changed login.php file for more secure 
|_http-server-header: Apache/2.4.41 (Ubuntu)
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 332.80 seconds
```

So we have this relevant information: 
1. The port `80` is open and listening 
2. The `.git` is reacheable

So if we search the ip in the broswer we can see this is the main page

![Index page DarkHoleV2](https://github.com/PassiDel/IPB_Cybersecurity_CTF/blob/main/ctf4/imagen_2022-06-17_095914094.png)

In this page there is any information relevant or important for us, so we look now in the login.php

![Login page DarkHoleV2](https://github.com/PassiDel/IPB_Cybersecurity_CTF/blob/main/ctf4/imagen_2022-06-17_100010931.png)

Here we can see there is a login page, but we don´t have any credential yet, so we can´t do nothing here.
Lets move to the last thing we can see here the directory `.git`

![.git page DarkHoleV2](https://github.com/PassiDel/IPB_Cybersecurity_CTF/blob/main/ctf4/imagen_2022-06-17_100133653.png)

We can see  in the git history  in the directory `logs` and in the file `COMMIT_EDITMSG` we can read .
>i changed login.php file for more secure

So now that we know there is a vulnerability we want to see the content of the commmits done. There is a usefull tool call git-dumper to see the content of the commits.

So we install it first.
```bash
$ pip install git-dumper

$ git-dumper http://192.168.10.17 ~/website
```
Now that we have stored the content of the web in the our directory `~/website` we are runing the command `git diff` and the commit id for seeing the the login.php that were before.
```bash
$ git diff a4d900a8d85e8938d3601f3cef113ee293028e10

diff --git a/login.php b/login.php
index 8a0ff67..0904b19 100644
--- a/login.php
+++ b/login.php
@@ -2,7 +2,10 @@
 session_start();
 require 'config/config.php';
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
-    if($_POST['email'] == "lush@admin.com" && $_POST['password'] == "321"){
+    $email = mysqli_real_escape_string($connect,htmlspecialchars($_POST['email']));
+    $pass = mysqli_real_escape_string($connect,htmlspecialchars($_POST['password']));
+    $check = $connect->query("select * from users where email='$email' and password='$pass' and id=1");
+    if($check->num_rows){
         $_SESSION['userid'] = 1;
         header("location:dashboard.php");
         die();

```
We use this id because it has as commit message .
>   I added login.php file with default credentials
 
 So now that we have the credentials `lush@admin.com` and `321` as user and password.

 Now we login in and there it is the profil page of our user.
![user page DarkHoleV2](https://github.com/PassiDel/IPB_Cybersecurity_CTF/blob/main/ctf4/imagen_2022-06-17_104736996.png)
