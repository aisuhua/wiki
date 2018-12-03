## log-slave-updates

开启从库上的 Binlog 日志，从库默认不会记录从主库同步过来的 Binlog 日志。

```cnf
[mysqld]
server-id = 2
bin_log = /var/log/mysql/mysql-bin.log
log_slave_updates = 1
```

如果该从库同时也要作为其他服务器的主库，搭建一个链式的复制，那么就需要打开这个选项。

- [option_mysqld_log-slave-updates](https://dev.mysql.com/doc/refman/5.7/en/replication-options-slave.html#option_mysqld_log-slave-updates)

## read-only

When the read_only system variable is enabled, the server permits no client updates except from users who have the SUPER privilege. This variable is disabled by default.

将从库修改为只读

```cnf
[mysqld]
read_only = 1
```

临时修改

```sql
set global read_only = 1
```

- [sysvar_read_only](https://dev.mysql.com/doc/refman/5.7/en/server-system-variables.html#sysvar_read_only)

## 指定复制的数据库或表

相关选项

```options
replicate-do-db
replicate-ignore-db
replicate-do-table
replicate-ignore-table
replicate-wild-do-table
```

只复制指定的表

```cnf
[mysqld]
replicate-do-table = tutorial.repl1
```

- [Replication Slave Options and Variables](https://dev.mysql.com/doc/refman/5.7/en/replication-options-slave.html)

## slave-skip-errors

自动跳过复制时的错误，可指定需跳过的错误码。

```sql
[mysqld]
slave_skip_errors = all
```

- [option_mysqld_slave-skip-errors](https://dev.mysql.com/doc/refman/5.7/en/replication-options-slave.html#option_mysqld_slave-skip-errors)
