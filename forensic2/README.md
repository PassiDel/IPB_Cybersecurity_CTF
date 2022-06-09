# Forensic Analysis 02
```bash
IP=192.168.10.14
PORT=9090
SSHUSER=m310023@alunos.ipb.pt
```

## port scan
```bash
$ nmap -p- -sV $IP
Starting Nmap 7.92 ( https://nmap.org ) at 2022-06-08 23:15 CEST
Nmap scan report for 192.168.10.14
Host is up (0.074s latency).
Not shown: 65532 closed tcp ports (conn-refused)
PORT     STATE SERVICE         VERSION
22/tcp   open  ssh             OpenSSH 8.2p1 Ubuntu 4ubuntu0.5 (Ubuntu Linux; protocol 2.0)
902/tcp  open  ssl/vmware-auth VMware Authentication Daemon 1.10 (Uses VNC, SOAP)
9090/tcp open  ssh             OpenSSH 8.9p1 Ubuntu 3 (Ubuntu Linux; protocol 2.0)
Service Info: OS: Linux; CPE: cpe:/o:linux:linux_kernel

Service detection performed. Please report any incorrect results at https://nmap.org/submit/ .
Nmap done: 1 IP address (1 host up) scanned in 60.94 seconds
```

## ssh
```bash
ssh-copy-id -p $PORT $SSHUSER@$IP
ssh -Y -p $PORT $IP -v -l $SSHUSER
```

## wireshark

```bash
$ ssh -Y -p $PORT 192.168.10.14 -v -l $SSHUSER tcpdump -U -w - ! port $PORT | wireshark -i - -k
```

setup filter `eth.src == 08:00:27:38:6b:da` (the mac of 192.168.10.150).
only pings, mostly 42 bytes long, but one 82 bytes long containing a base64 string

see [capture](capture.pcapng)

```hex
0000   ff ff ff ff ff ff 08 00 27 38 6b da 08 00 45 00   ........'8k...E.
0010   00 44 00 01 00 00 40 01 e4 80 c0 a8 0a 96 c0 a8   .D....@.........
0020   0a 51 08 00 0d 5e 00 00 00 00 59 33 6c 69 5a 58   .Q...^....Y3liZX
0030   4a 6a 64 47 5a 6b 65 33 41 78 62 6a 5a 66 4d 47   JjdGZke3AxbjZfMG
0040   5a 66 5a 44 4d 30 4e 32 68 66 62 54 52 75 66 51   ZfZDM0N2hfbTRufQ
0050   3d 3d                                             ==

```

```bash
$ echo "Y3liZXJjdGZke3AxbjZfMGZfZDM0N2hfbTRufQ==" | base64 -d
cyberctfd{p1n6_0f_d347h_m4n}
```

`cyberctfd{p1n6_0f_d347h_m4n}`