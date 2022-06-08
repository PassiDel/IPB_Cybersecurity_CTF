# Cryptography 05
morse code

one line has `=` at the end so base64:
```bash
$ echo "VGhlIGZsYWcgaXMgaGlkZGVuIGluIE1vcnNlIGNvZGUsIGdvb2QgbHVjay4=" | base64 -d

The flag is hidden in Morse code, good luck.

$ cat flag.txt | cut -c60 | awk '{print}' ORS='' | tr ';' ' ' | tr '_' '-' 
- .... . ..-. .-.. .- --. .. ... ..- .-- --=-.. --- - .-.. ----. .--- .-.. --- ..- ....
```

if the `=` is a space then the result is `THEFLAGISUWMDOTL9JLOUH`, if its not a space then `THEFLAGISUW8OTL9JLOUH`.

`THE FLAG IS UW8OTL9JLOUH` so `cyberctfd{uw8otl9jlouh}`,
`THE FLAG IS UWMDOTL9JLOUH` so `cyberctfd{uwmdotl9jlouh}`

both wrong lol.