停止进程组

```sh
shell> supervisorctl stop download:*
```

进程配置

```conf
[program:STATIS_DISPATCHER]
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