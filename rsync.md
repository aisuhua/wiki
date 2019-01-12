## 安装

安装

```sh
apt-get install rsync
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
service rsync restart
```

## 使用示例

同步本地文件

```sh
rsync -avz /data /backup
```

将本地文件同步到远程服务器

```sh
rsync -avz --exclude="*.log" --delete --progress test 192.168.1.2::www
```

支持断点续传

```sh
rsync -avzP test 192.168.1.2::www
```

将文件的权限和时间一起同步过去

```sh
rsync -vzrtopg --progress test 192.168.1.2::www
```


- [rsync命令](http://man.linuxde.net/rsync)
