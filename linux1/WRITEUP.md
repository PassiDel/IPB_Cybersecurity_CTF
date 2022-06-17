\clearpage
# Linux
## Linux 01
> Can you capture the flag?
> 
> Execute the following command to obtain a reverse shell on your local machine.
> 
> nc 192.168.10.7 7777

It is a reverse shell, where most commands have been changed to `cowsay`. Exceptions are `which`, `dir` and `echo`.

`dir` shows that there is a file called `flag.txt`, `echo` is used to print the content out.

```bash
user @ csictf: $ dir
flag.txt  script.sh  start.sh

user @ csictf: $ echo "$(<flag.txt)"
cyberctfd{d4mn_1_h473_c0w5}	
```