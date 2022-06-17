## Linux 03
> I should have really named my files better. I thought I've hidden the flag, now I can't find it myself. (Wrap your flag in cyberctfd{})
> 
> ssh user1@192.168.10.6
> 
> The password is cyberctfdpassword123 .


First we try a simple search commnand, to show any file that contains the character `ctf`.

```bash
find . -type f -exec grep -Hn "ctf" {} \;

```

With it, a file called `MITS1KT3` is shown that contains the string
```
cyberctfd{not_the_flag}{user2:AAE976A5232713355D58584CFE5A5}
```

With this information we switch to the second user.
```bash
user1@597bad1ae3ec:~$ su user2
Password: AAE976A5232713355D58584CFE5A5

user2@597bad1ae3ec:/user1$ cd

user2@597bad1ae3ec:~$ ls -la
total 3708
drwxr-x--- 1 root user2   4096 May 21 11:17 .
drwxr-xr-x 1 root root    4096 Jun 17 07:15 ..
-rwxr-x--- 1 root user2 756782 May 21 11:14 adgsfdgasf.js
-rwxr-x--- 1 root user2 756782 May 21 11:14 fadf.x
-rwxr-x--- 1 root user2 756782 May 21 11:14 janfjdkn.txt
-rwxr-x--- 1 root user2 756782 May 21 11:14 notflag.txt
-rwxr-x--- 1 root user2 756798 May 21 11:14 sadsas.tx

```
From that listing we see that there is a size difference in `sadsas.tx`. To show the difference `diff` is used.

```
$ diff fadf.x sadsas.tx 
42391a42392
> th15_15_unu5u41
```

This string looks like the searched flag.