# Linux 03

>IP=192.168.10.6
>SSHUSER=user1
>PW=cyberctfdpassword123

As long as `/user1` has a lot of large files, we decided to download locally all the files.

First we try a simple search commnand.

`find . -type f -exec grep "ctf" {} \;`

We saw the file [`MITS1KT3`](files/MITS1KT3) and realised it has different size and contains `cyberctfd{not_the_flag}{user2:AAE976A5232713355D58584CFE5A5}`

Then, we tried the following command, for finding every file in the current directory which is not empty.

```bash
$ find . -type f -exec wc -l {} \;
86771 ./fadf.x
86771 ./notflag.txt
86772 ./sadsas.tx
86771 ./janfjdkn.txt
86771 ./adgsfdgasf.js
```

And we search for the difference between `fadf.x` and `sadsas.tx` as they have different number of lines.

```
$ diff fadf.x sadsas.tx 
42391a42392
> th15_15_unu5u41
```

Flag: `cyberctfd{th15_15_unu5u41}`
