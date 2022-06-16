## Cryptography 04
> The Flag has been encoded using the encode.py script. Can you reverse engineer the script and discover the Flag?

The challenge supplies two files: the encoded `flag.txt` and the encoder `encode.py`.

The encoder encodes an input string by adding `13` or `0xd` to the byte of the ascii representation of each character. From this byte list the python string output (which is a comma and space seperated string of the decimal representation of each byte), without the opening and closing brackets, is encoded using base64. 
```py
import base64

encode = list()
store = str()

message = input(": ")
message_bytes = message.encode('ascii')

for a in message_bytes:
    num = int(a + 13)
    encode.append(num)

store = str(encode)[1:-1]
base64_bytes = base64.b64encode(store.encode('ascii'))
base64_message = base64_bytes.decode('ascii')

print(base64_message)
```

This can be reversed by first decoding the base64 input, splitting the string by `, ` and subtracting `13` from each byte in that list. Since this byte list is a character list, it can be displayed as a string.

```py
import base64

with open('flag.txt') as ff:
	base64_message = ff.read()
	base64_bytes = base64_message.encode('ascii')

	store = base64.b64decode(base64_bytes).decode('ascii')

	encode = store.split(', ')


	message = str()

	for a in encode:
		num = int(a) - 13
		message += chr(num)

	print(message)
```

As expected, the resulting output is the searched flag.
```base
$ python decode.py 
cyberctfd{1nv3r53_py7h0n}
```