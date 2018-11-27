# 日志

MySQL 主要有 4 种日志：查询日志、错误日志、慢查询日志 和 BINLOG 日志。

my.conf 中与日志相关的默认配置选项：

```nginx
[mysqld]
#
# * Logging and Replication
#
# Both location gets rotated by the cronjob.
# Be aware that this log type is a performance killer.
# As of 5.1 you can enable the log at runtime!
#general_log_file        = /var/log/mysql/mysql.log
#general_log             = 1
#
# Error log - should be very few entries.
#
log_error = /var/log/mysql/error.log
#
# Here you can see queries with especially long duration
#log_slow_queries       = /var/log/mysql/mysql-slow.log
#long_query_time = 2
#log-queries-not-using-indexes
#
# The following can be used as easy to replay backup logs or for replication.
# note: if you are setting up a replication slave, see README.Debian about
#       other settings you may need to change.
#server-id              = 1
#log_bin                        = /var/log/mysql/mysql-bin.log
expire_logs_days        = 10
max_binlog_size   = 100M
#binlog_do_db           = include_database_name
#binlog_ignore_db       = include_database_name
```

## 查询日志

打开查询日志，它记录了客户端执行的所有 SQL 语句。

```conf
[mysqld]
general_log_file = /var/log/mysql/mysql.log
general_log = 1
```

查看日志

```shell
shell> tail -f /var/log/mysql/mysql.log
```

查询的日志量一般比较大，对服务器性能有所影响，所以一般在生产环境下不会开启。

## 错误日志

查看错误日志

```shell
shell> more /var/log/mysql/error.log
```

它记录了 mysqld 在启动或停止以及在运行过程中发生严重错误的相关信息。

## 慢查询日志

打开慢查询日志

```conf
[mysqld]
log_slow_queries = /var/log/mysql/mysql-slow.log
long_query_time = 2
log-queries-not-using-indexes
```

记录执行时间超过 2 秒的语句，获取表锁定的时间不算执行时间。`log-queries-not-using-indexes` 表示要记录没有使用索引进行查询的慢查询语句。

查看 long_query_time 的值

```sql

```
