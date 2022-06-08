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
