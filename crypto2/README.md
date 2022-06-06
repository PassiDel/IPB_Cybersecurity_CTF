# Cryptography 02

Download `flag.zip`. Password protected zip archive.

According to hint the password is 10 char long and has to be of format (x is unknown but numeric):
```
!xxx_WTx`x
```

Complexity is 10^5 so 100.000, which is bruteforce-able.

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

resulting password is
```
!628_WT9`0
```

file content is base64 encoded so `cat flag | base64 -d`

`cyberctfd{7h15_15_7h3_fl46_c4p741n}`