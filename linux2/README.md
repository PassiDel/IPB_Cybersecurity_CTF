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

