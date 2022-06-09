# Forensic Analysis 01

Download pcap file, which can be opened with wireshark.
Instead of spending time and searching, i output all strings of this binary file and search for the known flag format.
```bash
strings analise_forense_1.pcap | grep cyberctfd

csrfmiddlewaretoken=qcnY4Ia8LXUR80tkeo24gbycYQctyOCkMdRn1uL8QtrMEfXtVpnAkOzJTnnC3yCq&username=admin&password=cyberctfd%7Bn07_50_53cur3%7Dj
```
this shows a urlencoded flag (apparently it is in a url/http request).

the full command to decode and strip is `strings analise_forense_1.pcap | grep cyberctfd | python3 -c "import sys; from urllib.parse import unquote; print(unquote(sys.stdin.read()));" | grep -o "cyberctfd{.*}"`.

explained:
```bash
strings analise_forense_1.pcap | # output all strings
grep cyberctfd | # search for string with flag format
python3 -c "import sys; from urllib.parse import unquote; print(unquote(sys.stdin.read()));" | # urldecode
grep -o "cyberctfd{.*}" # parse the clean full flag
```


`cyberctfd{n07_50_53cur3}`