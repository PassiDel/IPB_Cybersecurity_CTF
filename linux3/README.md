# Linux 03
```bash
IP=192.168.10.6
SSHUSER=user1
PW=cyberctfdpassword123
```

`/user1` has a lot of pretty large files. so copy everything locally to better work here.

`find . -type f -exec grep "ctf" {} \;`

the file [`MITS1KT3`](files/MITS1KT3) is of different size and contains `cyberctfd{not_the_flag}{user2:AAE976A5232713355D58584CFE5A5}`

```bash
$ find . -type f -exec wc -l {} \;
86771 ./fadf.x
86771 ./notflag.txt
86772 ./sadsas.tx
86771 ./janfjdkn.txt
86771 ./adgsfdgasf.js

$ diff fadf.x sadsas.tx 
42391a42392
> th15_15_unu5u41
```

`cyberctfd{th15_15_unu5u41}`