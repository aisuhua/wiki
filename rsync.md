
/etc/rsyncd.conf 

```shell
shell> cat /etc/rsyncd.conf 
uid=root
gid=root
max connections=100
use chroot=no
log file=/var/log/rsyncd.log
pid file=/var/run/rsyncd.pid
lock file=/var/run/rsyncd.lock
secrets file = /etc/rsyncd.sec
read only = no
hosts allow = *
# hosts deny = *

[www]
path = /www/web/xdebug
```

restart

```shell
shell> sudo service rsync restart
```

将本地文件备份到远程服务器

```shell
shell> rsync -avz --exclude="*.log" --progress test suhua@192.168.1.229::www
```
