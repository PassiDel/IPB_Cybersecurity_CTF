# Cryptography 05
Morse code

> The flag is hidden on this file, can you decode it?

We realised one of the lines has `=` at the end so we tried base64 decode:
```bash
$ echo "VGhlIGZsYWcgaXMgaGlkZGVuIGluIE1vcnNlIGNvZGUsIGdvb2QgbHVjay4=" | base64 -d

The flag is hidden in Morse code, good luck.
```

Then, we cut the last character of the lines, which symbols corresponds to morse code equivalences as follows:

> - ";" -> " "
> - "_" -> "-"
> - "." -> "."


```
$ cat flag.txt | cut -c60 | awk '{print}' ORS='' | tr ';' ' ' | tr '_' '-' 
- .... . ..-. .-.. .- --. .. ... ..- .-- --=-.. --- - .-.. ----. .--- .-.. --- ..- ....
```

Using a classic [morse decorder](https://morsedecoder.com/).

We considered if `=` was not an space so the output is:

```
THE FLAG IS UW8OTL9JLOUH
```

Flag: `cyberctfd{UW8OTL9JLOUH}`
