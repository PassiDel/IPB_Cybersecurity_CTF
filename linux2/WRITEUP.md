TASK -- Linux 2

Can you capture the flag?
Execute the following command to obtain a reverse shell on your local machine.

```bash
$ nc 192.168.10.7 9999
```
This command give us access with a reverse shell yo the machine with `ip 192.168.10.7`
once you have the shell, the first thing you can read is the text Where am I?.
The first command executed was ls you can see a directory called ytdl I had a look in there and I couldn´t find nothing special.
So I moved around the directories and found out that the directory `/root` has a readable hidden directory `.ssh` which store the followings files:
* `authorized_keys` Specifies the SSH keys that can be used for logging into the user account for which the file is configured
* `id_rsa` Here is stored the private key of the machine
* `id_rsa.pub` Here is stored the public key of the machine
As u can use ```bash $ cat id_rsa ``` and you can read the private key, we have two options:
1. Copy this key to our machine
2. Use this key locally to connect as root
The optimal option is to connect locally via `ssh` to the machine so we use.
```bash 
$ ssh root@localmachine -i /root/.ssh/id_rsa 
```


