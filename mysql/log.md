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
#slow_query_log = 1
#slow_query_log_file = /var/log/mysql/mysql-slow.log
#long_query_time = 2
#log_queries_not_using_indexes = 0
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

查询日志记录了客户端执行的所有 SQL 语句。

```conf
[mysqld]
general_log_file = /var/log/mysql/mysql.log
general_log = 1
```

查询的日志量一般比较大，对服务器性能有所影响，在生产环境下一般不会开启。

## 错误日志

错误日志记录了 mysqld 在启动或停止以及在运行过程中发生的所有错误信息。

```conf
[mysqld]
log_error = /var/log/mysql/error.log
```

## 慢查询日志

记录执行时间超过 2 秒的语句（注意：获取表锁定的时间不算执行时间）。

```conf
[mysqld]
slow_query_log = 1
slow_query_log_file = /var/log/mysql/mysql-slow.log
long_query_time = 2
```

在上面的基础上，还可以记录所有没有使用索引的 SQL 语句。

```
[mysqld]
log_queries_not_using_indexes = 1
```

慢查询时间阀值

```sql
 show variables like "long_query_time";
```

动态修改慢查询时间阀值

```sql
set global long_query_time = 5;
```

分析日志

```sh
mysqldumpslow /var/log/mysql/mysql-slow.log
```

按慢查询语句的执行次数进行排序

```sh
mysqldumpslow -s c /var/log/mysql/mysql-slow.log 
```

- [The Slow Query Log](https://dev.mysql.com/doc/refman/5.7/en/slow-query-log.html)
