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

