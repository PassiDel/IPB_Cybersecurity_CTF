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