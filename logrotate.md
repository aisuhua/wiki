## 配置示例

PHP 业务日志 

```sh
shell> vim /etc/logrotate.d/log-file
/var/log/log-file {
    hourly
    rotate 2
    maxsize 512M
    missingok
    compress
    delaycompress
    notifempty
    create 664 www-data www-data
    sharedscripts
}
```

- [Linux日志文件总管——logrotate](https://linux.cn/article-4126-1.html)

PHP-FPM 日志

```sh
shell> vim /etc/logrotate.d/php7.2-fpm
/var/log/php7.2-fpm.log {
    weekly
    rotate 12
    missingok
    notifempty
    compress
    delaycompress
    postrotate
        /usr/lib/php/php7.2-fpm-reopenlogs
    endscript
}
```

- [[php7.0-fpm] wrong log path in logrotate config](https://github.com/oerdnj/deb.sury.org/issues/221)

Nginx 访问日志

```sh
shell> vim /etc/logrotate.d/nginx
/var/log/nginx/*.log {
    hourly
    rotate 1
    maxsize 512M
    missingok
    compress
    delaycompress
    notifempty
    create 664 www-data www-data
    postrotate
        /etc/init.d/nginx reload
    endscript
}
```

Linux 系统日志

```sh
shell> vim /etc/logrotate.d/rsyslog
/var/log/syslog {
    rotate 7
    daily
    missingok
    notifempty
    delaycompress
    compress
    postrotate
        invoke-rc.d rsyslog rotate > /dev/null
    endscript
}

/var/log/mail.info
/var/log/mail.warn
/var/log/mail.err
/var/log/mail.log
/var/log/daemon.log
/var/log/kern.log
/var/log/auth.log
/var/log/user.log
/var/log/lpr.log
/var/log/cron.log
/var/log/debug
/var/log/messages {
    rotate 4
    weekly
    missingok
    notifempty
    compress
    delaycompress
    sharedscripts
    postrotate
        invoke-rc.d rsyslog rotate > /dev/null
    endscript
}
```


## 参考文献

- [logrotate/logrotate](https://github.com/logrotate/logrotate)
- [配置 logrotate 的终极指导](https://linux.cn/article-8227-1.html)
- [运维中的日志切割操作梳理（Logrotate/python/shell脚本实现）](https://www.cnblogs.com/kevingrace/p/6307298.html)
- [Hourly rotation of files using logrotate? [closed]](https://stackoverflow.com/questions/25485047/hourly-rotation-of-files-using-logrotate/25485313)
