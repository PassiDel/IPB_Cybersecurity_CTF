# Linux 01
```bash
nc 192.168.10.7 7777
```
Is a reverse shell. Most commands are replaced with cowsay (like ls or cat).

unsucessfull tries:
```bash
ls
cat flag
cat flag.txt
cowsay flag
`ls`
cd
pwd
cowsay test; ls
```

Working is `which`, `man`.
With `dir` the folder content was printed:
```bash
user @ csictf: $ 
dir
flag.txt  script.sh  start.sh
```
so the flag is called `flag.txt`, pretty much as expected. after trying
```bash
less flag.txt
cowsay flag.txt
```
I figured that `echo` works. so googling "bash echo filecontent" https://unix.stackexchange.com/a/195484

i tried `echo "$(<flag.txt)"`, which worked.


`cyberctfd{d4mn_1_h473_c0w5}`