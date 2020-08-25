## Ubuntu 常见问题

sshd: no hostkeys available — exiting

```shell
ssh-keygen -A
```

A public key file has not been specified for this session,Would you like to

```
PasswordAuthentication yes
```

ssh 允许密码登录

```
# vim /etc/ssh/sshd_config
PasswordAuthentication yes
```

安装和启动 docker

```
apt-get install docker.io
dockerd
```



- [System has not been booted with systemd as init system (PID 1).](https://github.com/MicrosoftDocs/WSL/issues/457#issuecomment-642418572)
