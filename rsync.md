
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
path = /www/web
```

重启

```shell
shell> sudo service rsync restart
```

同步本地文件

```shell
shell> rsync -avz /data /backup
```

将本地文件同步到远程服务器

```shell
shell> rsync -avz --exclude="*.log" --progress test suhua@192.168.1.2::www
```

Options:

```shell
--delete  delete extraneous files from destination dirs
--exclude 可使用通配符（子目录有也会匹配到），也可以指定完整的目录
```

