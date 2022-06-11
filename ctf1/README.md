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
