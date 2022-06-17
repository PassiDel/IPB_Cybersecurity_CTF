## Web Hacking 02
> The flag is hidden in the following website, can you get it?
> 
> http://192.168.10.5:8090

When accessing the website, a Cookie is set `flavour=c3RyYXdiZXJyeQ%3D%3D`. The `%3D` is urlencoded for `=`. The Content is encoded using base64 and decodes to `strawberry`

```bash
$ echo "c3RyYXdiZXJyeQ==" | base64 -d
strawberry
``` 

The website shows text that reads:
> My nephew is a fussy eater and is only willing to eat chocolate cookies. Any other flavor and he throws a tantrum.

So if the cookie gets changed to `chocolate` base64 encoded, the flag will be returned:

```bash
$ echo "chocolate" | base64 
Y2hvY29sYXRl

$ curl http://192.168.10.5:8090 --cookie flavour=Y2hvY29sYXRl
cyberctfd{ch0c0l473_c00k135_4r3_my_f4v0r173}
```
