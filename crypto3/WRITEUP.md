## Cryptography 03
> The Flag is hidden somewhere on the website, can you find it?
> 
> http://192.168.10.5
> 
> Make sure to wrap the flag with cyberctfd{} before submitting the answer.

When opening the website corresponding to our IP, we realise that the title is "enigma machine" and the content is just a template.


The website sets an unusual X-Frame-Options:
```bash
$ curl -I http://192.168.10.5                                                
HTTP/1.1 200 OK
Server: nginx/1.18.0
Date: Thu, 09 Jun 2022 00:14:18 GMT
Content-Type: text/html
Content-Length: 15611
Connection: keep-alive
Last-Modified: Sat, 21 May 2022 11:14:11 GMT
X-Frame-Options: M3 (model3) | B (reflector type) | I,IV,V (rotor types and order)
	| J,T,V (rotors initial value) | 1,1,1 (rotors ring setting)
```

The values of the X-Frame-Options corresponds to enigma settings, so we set them in an [online enigma decoder](https://cryptii.com/pipes/enigma-machine):

![Enginma Settings](crypto3/enigma2.png)

Afterwards, inspecting the page, we found out that the console of the page has a code which could match with the input of our machine. This `console.log()` is at the very end of the `main.js`.

```js

console.log("KPVBP DQRCI NYKWT JQTVY EUMUD YFZEN FXAMO ZECT");

```

Used as the input, the result is:
```md
thefl agisj fkjru iawxm vdzwy gkncm ltgkj dxse
the flag is jfkjruiawxmvdzwygkncmltgkjdxse

JFKJRUIAWXMVDZWYGKNCMLTGKJDXSE

```