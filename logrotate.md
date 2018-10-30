## 配置示例

PHP 业务日志 

```sh
shell> vim /etc/logrotate.d/log-file
/var/log/log-file {
    rotate 3
    hourly
    maxsize 500M
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
    rotate 12
    weekly
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


## 参考文献

- [logrotate/logrotate](https://github.com/logrotate/logrotate)
- [配置 logrotate 的终极指导](https://linux.cn/article-8227-1.html)
- [运维中的日志切割操作梳理（Logrotate/python/shell脚本实现）](https://www.cnblogs.com/kevingrace/p/6307298.html)
