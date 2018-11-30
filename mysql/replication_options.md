## log-slave-updates

开启从库上的 Binlog 日志，从库默认不会记录从主库同步过来的操作的 Binlog 日志。

```cnf
[mysqld]
server-id = 2
bin_log = /var/log/mysql/mysql-bin.log
log_slave_updates = 1
```

如果该从库同时也要作为其他服务器的主库，搭建一个链式的复制，那么就需要打开这个选项。

- [option_mysqld_log-slave-updates](https://dev.mysql.com/doc/refman/5.7/en/replication-options-slave.html#option_mysqld_log-slave-updates)
