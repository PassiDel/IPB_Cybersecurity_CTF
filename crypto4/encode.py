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