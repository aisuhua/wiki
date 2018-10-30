## php-fpm.conf

保持原样

## php.ini

```ini
[PHP]
upload_max_filesize = 30M
post_max_size = 30M
```

## www.conf

```ini
[www]
listen = 127.0.0.1:9000
pm = static
pm.max_children = 200
pm.max_requests = 200
pm.status_path = /phpfpm_status
ping.path = /phpfpm_ping
chdir = /
;slowlog = /var/log/phpfpm_slow.log
;request_slowlog_timeout = 1s
;request_terminate_timeout = 0
```
