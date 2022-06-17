\clearpage
# Web Hacking
## Web Hacking 01
> The flag is hidden in the following website, can you get it?
> 
> http://192.168.10.5:8080

The website is a simple html site with some styling.

```html
$ curl http://192.168.10.5:8080

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cascade</title>
    <link rel="stylesheet" href="/static/style.css">
</head>
<body>
    <h1>Welcome to CyberCTFd</h1>
    <div>CTFd: <a href="https://192.168.10.3">https://192.168.10.3</a></div>
    <div>Kali: <a href="https://www.kali.org/">https://www.kali.org/</a></div>
</body>
</html>
```

After a look at the stylesheet, the flag was found:

```css
$ curl http://192.168.10.5:8080/static/style.css

body {
    background-color: purple;
    text-align: center;
    display: flex;
    align-items: center;
    flex-direction: column;
}

h1, div, a {
    /* cyberctfd{w3lc0me_t0_cyb3rc7fd} */
    color: white;
    font-size: 3rem;
}
```