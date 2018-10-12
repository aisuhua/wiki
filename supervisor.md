停止进程组

```sh
shell> supervisorctl stop download:*
```

进程配置

```sh
shell> cat /etc/supervisor/conf.d/program.conf 
[program:PROCESS_NAME]
command:/usr/bin/php /www/web/demo.php
directory:%(here)s
process_name:%(program_name)s_%(process_num)s
numprocs:2
numprocs_start:0
startretries:20
redirect_stderr:true
stdout_logfile:AUTO
stdout_logfile_backups:0
stdout_logfile_maxbytes:1MB
autostart:true
autorestart:true
user:www-data
```

修复 too many open files to spawn 的错误

```sh
shell> vim /etc/supervisor/supervisord.conf
[supervisord]
minfds = 65535
```

- [Configuration File](http://supervisord.org/configuration.html)


