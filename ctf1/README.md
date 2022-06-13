# CTF 01
```bash
URL=https://192.168.10.18
```

# Recognisement
Using nmap ```nmap -v -Pn 192.168.10.18 ```

Output:
```bash 
Host discovery disabled (-Pn). All addresses will be marked 'up' and scan times may be slower.
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-11 07:05 EDT
Initiating Parallel DNS resolution of 1 host. at 07:05
Completed Parallel DNS resolution of 1 host. at 07:05, 0.00s elapsed
Initiating Connect Scan at 07:05
Scanning 192.168.10.18 [1000 ports]
Discovered open port 3306/tcp on 192.168.10.18
Discovered open port 22/tcp on 192.168.10.18
Discovered open port 111/tcp on 192.168.10.18
Discovered open port 80/tcp on 192.168.10.18
Discovered open port 443/tcp on 192.168.10.18
Completed Connect Scan at 07:05, 7.76s elapsed (1000 total ports)
Nmap scan report for 192.168.10.18
Host is up (0.078s latency).
Not shown: 995 filtered tcp ports (no-response)
PORT     STATE SERVICE
22/tcp   open  ssh
80/tcp   open  http
111/tcp  open  rpcbind
443/tcp  open  https
3306/tcp open  mysql

Read data files from: /usr/bin/../share/nmap
Nmap done: 1 IP address (1 host up) scanned in 7.81 seconds
```

I think is something about rpcbind port.

```bash
$ nmap -sV -Pn $IP -p-
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-13 15:58 CEST
Stats: 0:01:27 elapsed; 0 hosts completed (1 up), 1 undergoing Service Scan
Service scan Timing: About 87.50% done; ETC: 16:00 (0:00:11 remaining)
Stats: 0:01:52 elapsed; 0 hosts completed (1 up), 1 undergoing Service Scan
Service scan Timing: About 87.50% done; ETC: 16:00 (0:00:15 remaining)
Nmap scan report for 192.168.10.18
Host is up (0.0025s latency).
Not shown: 65527 closed tcp ports (conn-refused)
PORT     STATE SERVICE         VERSION
22/tcp   open  ssh             OpenSSH 7.4 (protocol 2.0)
66/tcp   open  http            SimpleHTTPServer 0.6 (Python 2.7.5)
80/tcp   open  http            Apache httpd 2.4.6 ((CentOS) OpenSSL/1.0.2k-fips mod_fcgid/2.3.9 PHP/5.4.16 mod_perl/2.0.11 Perl/v5.16.3)
111/tcp  open  rpcbind         2-4 (RPC #100000)
443/tcp  open  ssl/http        Apache httpd 2.4.6 ((CentOS) OpenSSL/1.0.2k-fips mod_fcgid/2.3.9 PHP/5.4.16 mod_perl/2.0.11 Perl/v5.16.3)
2403/tcp open  taskmaster2000?
3306/tcp open  mysql           MariaDB (unauthorized)
8086/tcp open  http            InfluxDB http admin 1.7.9

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 134.22 seconds
```