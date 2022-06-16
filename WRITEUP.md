---
title: "CTF Writeup"
author:
  - Pascal Syma^[City University of Applied Sciences Bremen, Germany, m310023@alunos.ipb.pt]
  - Adrian Sampedro Menchen^[Universidad de León, Spain, m310103@alunos.ipb.pt]
  - Lisardo Carretero Colmenar^[Universidad de Almería, Spain, m310063@alunos.ipb.pt]
  - Manuel Martinez Galera^[Universidad de Almería, Spain, m310083@alunos.ipb.pt]
date: 2022-06-19
title: |
  	\begin{figure}[H]
	%\centering
	\hfil%
	\includegraphics[width=7cm]{ipb.png}%
	\hfil%
	\includegraphics[width=7cm]{hsb.png}%
	\hfil%
	\hfil%
	\end{figure}
	\begin{figure}[H]
	%\centering
	\hfil%
	\includegraphics[height=2cm]{almeria.png}%
	\hfil%
	\includegraphics[height=2cm]{leon.png}%
	\hfil%
	\hfil%
	\end{figure}
	CTF Writeup
---
\begin{flushleft}
% 
\textbf{Instituto Politécnico de Bragança}\\
BIP Cybersecurity - Second Practical Assignment\\
Responsible: Tiago Pedrosa
\end{flushleft}

\pagenumbering{gobble}
\clearpage
\tableofcontents
\listoffigures
\newpage

\pagenumbering{arabic}
\setcounter{page}{1}

# Flags

- Cryptography 01: `cyberctfd{57r4n}`
- Cryptography 02: `cyberctfd{7h15_15_7h3_fl46_c4p741n}`
- Cryptography 03: `cyberctfd{JFKJRUIAWXMVDZWYGKNCMLTGKJDXSE}`
- Cryptography 04: `cyberctfd{1nv3r53_py7h0n}`
- Cryptography 05: `cyberctfd{UW8OTL9JLOUH}`
- CTF 01
	- User: `cyberctfd{r5KYSTQmMve4KVIoaeaVQH4NUfD7caR6}`
	- Root: `cyberctfd{cROGRMZeGLNFZ3XIfaeaKoT2OKTB4qhM}`
- CTF 02
	- User: `cyberctfd{WdnyIjucRPQY8fanmdvbklZpWVxZq1eJ}`
	- Root: `cyberctfd{t2dj9ONj6pB9uY7BBBsvayCGyXzsvwUF}`
- CTF 03
	- User: `cyberctfd{0yjA5vhJWYkTXX2UALbw8Q7yMKqASU73}`
	- Root: `cyberctfd{lBCz8J3tRZgCqUY3O8QQygKuIzURuLql}`
- CTF 04
	- User: `cyberctfd{ejkedSmQx5zEBJz9PjE8gTbPkHr839EY}`
	- Root: `cyberctfd{tuF9WIjTCUGyKe8JVKGmqSaDuHp4mQqc}`
- Forensic 01: `cyberctfd{n07_50_53cur3}`
- Forensic 02: `cyberctfd{p1n6_0f_d347h_m4n}`
- Forensic 03: `cyberctfd{[22/Dec/2016:16:31:51 +0300]}`
- Linux 01: `cyberctfd{d4mn_1_h473_c0w5}`
- Linux 02: `cyberctfd{1nd33d_wh3r3_w45_1}`
- Linux 03: `cyberctfd{th15_15_unu5u41}`
- Web Hacking 01: `cyberctfd{w3lc0me_t0_cyb3rc7fd}`
- Web Hacking 02: `cyberctfd{ch0c0l473_c00k135_4r3_my_f4v0r173}`
- Web Hacking 03: `cyberctfd{br0b0t_1s_pr3tty_c00l}`
- Web Hacking 04: `cyberctfd{d0n7_5pl17_m3_4p4r7}`
- Web Hacking 05: `cyberctfd{1_4m_r007}`

# Challenges
This CTF consists of 20 challenges in five categories: Cryptography, CTF, Forensic, Linux and Web Hacking.