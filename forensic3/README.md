# Forensic Analysis 03
```bash
IP=192.168.10.14
PORT=9090
```

Download [analise_forense_3.txt](analise_forense_3.txt), text file showing logs, 6540 lines. we search for a command injection


try finding something in path, mostly local file inclusion except for nslookup.
```
$ cat analise_forense_3.txt | cut -d ' ' -f4-8,11

[22/Dec/2016:16:33:39 +0300] "GET /index.php/component/search/?ordering=../../../../../../../../../../etc/passwd&searchphrase=all&searchword= HTTP/1.1" "http://192.168.4.161/DVWA"
[22/Dec/2016:16:19:10 +0300] "GET /index.php/component/content/?format=feed&type=atom&view=../.../.././../.../.././../.../.././../.../.././../.../.././../.../.././etc/passwd HTTP/1.1" "http://192.168.4.161/DVWA"
[22/Dec/2016:16:19:23 +0300] "GET /%26nslookup%20OENbNEQG%26'%5c\"`0%26nslookup%20OENbNEQG%26`'/2-uncategorised/1-testsayfasi HTTP/1.1" "-"
[22/Dec/2016:16:37:40 +0300] "POST /index.php/component/users/?task=../../../../../../../../../../etc/passwd%00.login HTTP/1.1" "http://192.168.4.161/DVWA"
[22/Dec/2016:16:35:54 +0300] "GET /index.php/component/search/?ordering=popular&searchphrase=any&searchword=..%c0%af..%c0%af..%c0%af..%c0%af..%c0%af..%c0%af..%c0%af..%c0%afetc/passwd HTTP/1.1" "http://192.168.4.161/DVWA"
[22/Dec/2016:16:19:49 +0300] "GET /index.php/component/mailto/?link=0011f090d89f910f3d24e9873575a8d16029ca3e&template=../..//../..//../..//../..//../..//../..//../..//../..//etc/passwd&tmpl=component HTTP/1.1" "http://192.168.4.161/DVWA"
[22/Dec/2016:16:37:43 +0300] "POST /index.php/component/users/?task=../..//../..//../..//../..//../..//../..//../..//../..//etc/passwd HTTP/1.1" "http://192.168.4.161/DVWA"
[22/Dec/2016:16:31:07 +0300] "GET /index.php/file:///etc/passwd/users/ HTTP/1.1" "http://192.168.4.161/DVWA"
[22/Dec/2016:16:19:51 +0300] "GET /index.php/component/mailto/?link=90ed7f9d717b47dafcf4b4c1bb9de7c57b1fb587&template=beez_20&tmpl=..%252F..%252F..%252F..%252F..%252F..%252F..%252F..%252F..%252F..%252Fetc%252Fpasswd%2500.jpg HTTP/1.1" "http://192.168.4.161/DVWA"

```
sort by user agent
```bash
$ cat analise_forense_3.txt | cut -d ' ' -f12- | uniq -c | sort -n
      1 "-""
      1 "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; w3af.sf.net)""
      1 "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; w3af.sf.net)""
      1 { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
      1 { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
      1 { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
      1 { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
    292 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
    323 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
    411 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
    424 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
    425 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
    577 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
   1805 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
   2276 "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
```

```
grep "echo -e" analise_forense_3.txt
"192.168.4.10 - - [22/Dec/2016:16:18:08 +0300] "GET /cgi-sys/entropybanner.cgi HTTP/1.1" 404 516 "() { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
"192.168.4.10 - - [22/Dec/2016:16:18:07 +0300] "GET / HTTP/1.1" 200 3358 "() { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
"192.168.4.10 - - [22/Dec/2016:16:18:08 +0300] "GET /cgi-sys/domainredirect.cgi HTTP/1.1" 404 517 "() { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
"192.168.4.10 - - [22/Dec/2016:16:18:07 +0300] "GET /admin.cgi HTTP/1.1" 404 500 "() { Referer; }; echo -e \"Content-Type: text/plain\\n\"; echo -e \"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\"" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
```

```bash
$ echo -e "\"\\0141\\0143\\0165\\0156\\0145\\0164\\0151\\0170\\0163\\0150\\0145\\0154\\0154\\0163\\0150\\0157\\0143\\0153\""
"acunetixshellshock"
```

maybe shellshock? but that are 4 occurrences.

count per ip
https://unix.stackexchange.com/a/246115

```bash
$ cat analise_forense_3.txt | cut -d ' ' -f1 | sort -n -t. -k1,1 -k2,2 -k3,3 -k4,4 | uniq -c | sort -n -r -s

   5404 "192.168.4.25
    689 "192.168.4.10
    363 "192.168.4.19
     84 "192.168.4.31
```


its not `cyberctfd{[22/Dec/2016:16:19:23 +0300]}` (from nslookup)