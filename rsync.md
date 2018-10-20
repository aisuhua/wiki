install

```sh
shell> apt-get install rsync
```

开启 rsync 服务 

```sh
shell> vim /etc/default/rsync
RSYNC_ENABLE=true
```

添加配置

```sh
shell> vim /etc/rsyncd.conf 
uid=root
gid=root
max connections=100
use chroot=no
log file=/var/log/rsyncd.log
pid file=/var/run/rsyncd.pid
lock file=/var/run/rsyncd.lock
secrets file = /etc/rsyncd.sec
read only = no
hosts allow = 192.168.1.0/24
hosts deny = *

[www]
path = /www/web
```

重启服务

```sh
shell> service rsync restart
```

同步本地文件

```sh
shell> rsync -avz /data /backup
```

将本地文件同步到远程服务器

```sh
shell> rsync -avz --exclude="*.log" --progress test suhua@192.168.1.2::www
```

Options:

```sh
--delete  delete extraneous files from destination dirs
--exclude 可使用通配符（子目录有也会匹配到），也可以指定完整的目录
```

