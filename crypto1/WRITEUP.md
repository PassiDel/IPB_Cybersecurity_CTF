## Cryptography 01
> The flag is hidden on this file, can you decode it?

The Challenge provides a `flag.zip` file to download. The zip-archive contains a file with an odd, long name:
```txt
--[----->+<]>---.-[--->+<]>+++.---[->+++<]>.+++.+++++++++++++.++++[
->+++<]>+.-[--->+<]>--.+++[->+++<]>+.--.[++>-----<]>+.-[++>---<]>--
.++.++[->++<]>.[-->+<]>-----.+++[->++<]>.[--->+<]>+++..txt
```

The name only contains the following characters: `+ - . < > [ ]`. Those characters are also the only valid commands for the programming language [brainfuck](https://en.wikipedia.org/wiki/Brainfuck).

Since this is a programming language, it can be executed using an [interpreter](https://gc.de/gc/brainfuck/).

![Brainfuck Interpreter](crypto1/brainfuck.png)

When executed, the resulting output string is `cyberctfd{57r4n}`, which is the searched flag.