# Web Hacking 03
```bash
IP=192.168.10.5
PORT=8100
```

# Browser
Open `http://$IP` in browser. shows packe with link to github about a bot who can execute shell commands.

Reponse Headers:
```
Server: Werkzeug/2.1.2 Python/3.8.13
```
"Werkzeug" is german for "tool".

Link to git repo isn't very helpfull.

## gobuster
```bash
$ gobuster dir -u http://$IP:$PORT -w /usr/share/wordlists/dirb/common.txt
===============================================================
Gobuster v3.1.0
by OJ Reeves (@TheColonial) & Christian Mehlmauer (@firefart)
===============================================================
[+] Url:                     http://192.168.10.5:8100
[+] Method:                  GET
[+] Threads:                 10
[+] Wordlist:                /usr/share/wordlists/dirb/common.txt
[+] Negative Status codes:   404
[+] User Agent:              gobuster/3.1.0
[+] Timeout:                 10s
===============================================================
2022/06/08 22:37:15 Starting gobuster in directory enumeration mode
===============================================================
/robots.txt           (Status: 200) [Size: 140]
                                               
===============================================================
2022/06/08 22:38:25 Finished
===============================================================
```

## port scan
```bash
 nmap -p- $IP
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-08 22:38 CEST
Nmap scan report for 192.168.10.5
Host is up (0.074s latency).
Not shown: 65530 closed tcp ports (conn-refused)
PORT     STATE SERVICE
80/tcp   open  http
5000/tcp open  upnp
8080/tcp open  http-proxy
8090/tcp open  opsmessaging
8100/tcp open  xprint-server

Nmap done: 1 IP address (1 host up) scanned in 65.58 seconds
```


## gobuster 2
```bash
$ curl http://$IP:$PORT/robots.txt                                          
# Hey there, you're not a robot, yet I see you sniffing through this file.
# SEO you later!
# Now get off my lawn.

Disallow: /fade/to/black

$ curl http://$IP:$PORT/fade/to/black
cyberctfd{br0b0t_1s_pr3tty_c00l}
```

`cyberctfd{br0b0t_1s_pr3tty_c00l}`