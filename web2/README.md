# Web Hacking 02
```bash
URL=http://192.168.10.5:8090
```

## Browser
Open website with browser. Site sends Cookie `flavour=c3RyYXdiZXJyeQ%3D%3D; Path=/`. URL and base64 encoded

```js
console.log(atob("c3RyYXdiZXJyeQ=="))
# strawberry
```

Hint says "only likes chocolate".
```js
console.log(btoa("chocolate"))
# Y2hvY29sYXRl
```

Change cookies using Devtools to `Y2hvY29sYXRl`. Reload page.


`cyberctfd{ch0c0l473_c00k135_4r3_my_f4v0r173}`