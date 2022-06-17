## Forensic Analysis 01
> Analyze the file, and discover the Flag hidden in it.

The challenge includes a downloadable file called `analise_forense_1.pcap`, which is a packet capture and could be read using Wireshark.

To not waste time searching in Wireshark, the bash command `strings` is used. It shows all printable characters of binary files.

```bash
strings analise_forense_1.pcap | grep cyberctfd

csrfmiddlewaretoken=qcnY4Ia8LXUR80tkeo24gbycYQctyOCkMdRn1uL8QtrMEfXtVpnAkOzJTnnC3yCq
	&username=admin&password=cyberctfd%7Bn07_50_53cur3%7Dj
```

The flag is send as password and is urlencoded, since it is used in a url.
```bash
strings analise_forense_1.pcap | # output all strings
grep cyberctfd | # search for string with flag format
python3 -c "import sys; from urllib.parse import unquote; print(unquote(sys.stdin.read()));" |
	# urldecode
grep -o "cyberctfd{.*}" # parse the clean full flag

cyberctfd{n07_50_53cur3}
```