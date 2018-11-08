## 安装

```sh
shell> apt-get install supervisor
```

## 操作

添加进程组

```sh
shell> cat /etc/supervisor/conf.d/program.conf 
[program:foo_worker]
command=/usr/bin/php /www/web/foo/worker.php
directory=%(here)s
process_name=%(program_name)s_%(process_num)s
numprocs=1
numprocs_start=0
startretries=20
redirect_stderr=true
stdout_logfile=AUTO
stdout_logfile_backups=0
stdout_logfile_maxbytes=1MB
autostart=true
autorestart=true
user=www-data
```

更新配置

```sh
shell> supervisorctl update
```

查看进程状态

```sh
shell> supervisorctl status
```

启动进程

```sh
shell> supervisorctl start foo_worker:foo_worker_0
shell> supervisorctl start foo_worker:*
```

停止进程

```sh
shell> supervisorctl stop foo_worker:foo_worker_0
shell> supervisorctl stop foo_worker:*
```

## 其他

修复 too many open files to spawn 的错误

```sh
shell> vim /etc/supervisor/supervisord.conf
[supervisord]
minfds = 65535
```

- [Configuration File](http://supervisord.org/configuration.html)

## 参考文献

- [Supervisor: A Process Control System](http://supervisord.org/index.html)
