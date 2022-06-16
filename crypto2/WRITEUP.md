## Cryptography 02
>I sent this file to a friend, and to make sure nobody else would see what's inside it, I protected it with a password, but forgot it.
>
>The password was 10 characters long, it had special characters, uppercase letters and numbers.
>
>I remember the first character was "!", the fifth was "_" followed by "WT", and the ninth was "`". Everything else was composed of numbers.
>
>For an extra layer of protection, I also encoded the message.


The Challenge supplies a zip-archive with an encrypted file called `flag`.

Based on the hint in the description it can be concluded, that the used password is ten characters long, with five fixed and five numerical charaters. This results in a maximum number of combinations of `10^5 = 100.000`.

The format of the password is ```
!xxx_WTx`x
```, where `x` is an unknown number from zero to nine (`[0:9]`).


To brute-force this password, a python script is used:
```py
from zipfile import ZipFile

with ZipFile('flag.zip') as zf:
	for i in range(10**5):
		password = "!{0}{1}{2}_WT{3}`{4}".format(
			(i // 10000) % 10,
			(i // 1000) % 10,
			(i // 100) % 10,
			(i // 10) % 10,
			(i // 1) % 10)
		try:
			zf.extractall(pwd=bytes(password,'utf-8'))
			print(password)
			exit(1)
		except:
			pass

```

This script will loop over all possible combination and tries to extract the encrypted file. If successfull, the used password is printed out.


After a few seconds the script stops and shows the used password:
```
!628_WT9`0
```

The file is a ASCII text file. Since the string only contains lower- and uppercase characters and an equals sign, this is a strong indicator for a base64-encoded string. After decoding the flag is shown.

```bash
$ file flag
flag: ASCII text, with no line terminators

$ cat flag
Y3liZXJjdGZkezdoMTVfMTVfN2gzX2ZsNDZfYzRwNzQxbn0=

$ cat flag | base64 -d
cyberctfd{7h15_15_7h3_fl46_c4p741n}
```