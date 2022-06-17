# Forensic Analysis 03
```bash
IP=192.168.10.14
PORT=9090
```

Download [analise_forense_3.txt](analise_forense_3.txt), text file showing logs, 6540 lines. we search for a command injection.

We realised that were too many command injection so it must me unique.
With the help of this [site]https://www.cobalt.io/blog/a-pentesters-guide-to-command-injection , we started looking for a unique output searching for those common words.

Finally, with the key word"arg" we manage to found an output that also matches with the prerequisites that must be a command injection trace.

```bash
$ cat analise_forense_3.txt| grep arg 
"192.168.4.25 - - [22/Dec/2016:16:31:51 +0300] "GET /index.php?arg=8.8.8.8;system('id') HTTP/1.1" 500 1983 "-" "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.21 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.21""
```

Flag: `cyberctfd{[22/Dec/2016:16:31:51 +0300]}`
