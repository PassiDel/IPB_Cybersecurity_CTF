## Web Hacking 03
> "People who get violent get that way because they can’t communicate."
> 
> http://192.168.10.5:8100

The website shows text about a telegram bot called BroBot with a link to a GitHub repository. Since there are no clues in neither the headers, source code nor git repository, a gobuster search is concluded.

The `robots.txt` sets a disallow to `/fade/to/black`, which contains the flag.

```bash
$ gobuster dir -u http://192.168.10.5:8100 -w /usr/share/wordlists/dirb/common.txt
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

$ curl http://192.168.10.5:8100/robots.txt
# Hey there, you're not a robot, yet I see you sniffing through this file.
# SEO you later!
# Now get off my lawn.

Disallow: /fade/to/black

$ curl http://192.168.10.5:8100/fade/to/black
cyberctfd{br0b0t_1s_pr3tty_c00l}
```