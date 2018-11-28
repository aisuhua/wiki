# 日志

MySQL 主要有 4 种日志：

- 查询日志；
- 错误日志；
- 慢查询日志
- BINLOG 日志。

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
general_log = 1
general_log_file = /var/log/mysql/mysql.log
```

查询的日志量一般比较大，对服务器性能有所影响，在生产环境下一般不会开启。

日志格式

```log
Time                          Id Command  Argument
2018-11-28T02:03:42.462719Z   4 Connect   root@localhost on  using Socket
2018-11-28T02:03:48.716655Z   4 Init DB   test
2018-11-28T02:03:54.227087Z   4 Query     select * from demo where id = 1
```

## 错误日志

错误日志记录了 mysqld 在启动或停止以及在运行过程中发生的所有错误信息。

```conf
[mysqld]
log_error = /var/log/mysql/error.log
```

日志格式

```log
2018-11-28T01:53:01.946004Z 0 [Warning] Failed to set up SSL because of the following...
2018-11-28T01:53:01.946043Z 0 [Note] Server hostname (bind-address): '0.0.0.0'; port: 3306
2018-11-28T01:53:02.506562Z 2 [Note] Access denied for user 'root'@'localhost' (using password: NO)
```

## 慢查询日志

记录执行时间超过 2 秒的语句（注意：获取表锁定的时间不算执行时间）。

```conf
[mysqld]
slow_query_log = 1
slow_query_log_file = /var/log/mysql/mysql-slow.log
long_query_time = 2
```

在前面的基础上，还可以记录所有没有使用到索引的 SQL 语句。

```
[mysqld]
log_queries_not_using_indexes = 1
```

日志格式

```log
# Time: 2018-11-28T02:15:49.606694Z
# User@Host: root[root] @ localhost []  Id:     4
# Query_time: 4.523855  Lock_time: 0.000395 Rows_sent: 5916  Rows_examined: 4011832
SET timestamp=1543371349;
select file_type, count(*) from demo group by file_type;
```

查看慢查询时间阀值

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

按慢查询语句的数量进行排序

```sh
mysqldumpslow -s c /var/log/mysql/mysql-slow.log 
```

- [The Slow Query Log](https://dev.mysql.com/doc/refman/5.7/en/slow-query-log.html)

## BINLOG 日志

binlog 日志记录了所有 DDL 和 DML 语句，但是不包括数据查询语句，它描述了数据的变更过程。

```conf
[mysqld]
server-id = 1
log_bin = /var/log/mysql/mysql-bin.log
```

日志格式

```log
# at 527
#181128 10:37:35 server id 1  end_log_pos 592 CRC32 0xa2915573  Anonymous_GTID  last_committed=1        sequence_number=2       rbr_only=yes
/*!50718 SET TRANSACTION ISOLATION LEVEL READ COMMITTED*//*!*/;
SET @@SESSION.GTID_NEXT= 'ANONYMOUS'/*!*/;
# at 592
#181128 10:37:35 server id 1  end_log_pos 664 CRC32 0xe9daa6cd  Query   thread_id=4     exec_time=0     error_code=0
SET TIMESTAMP=1543372655/*!*/;
BEGIN
/*!*/;
# at 664
#181128 10:37:35 server id 1  end_log_pos 724 CRC32 0x57a9a396  Table_map: `test`.`demo` mapped to number 110
# at 724
#181128 10:37:35 server id 1  end_log_pos 869 CRC32 0x57411325  Delete_rows: table id 110 flags: STMT_END_F

BINLOG '
b//9WxMBAAAAPAAAANQCAAAAAG4AAAAAAAEABHRlc3QABGRlbW8ABggP/ggPDwgAAv6gUAD8AwCW
o6lX
b//9WyABAAAAkQAAAGUDAAAAAG4AAAAAAAEAAgAG/8AFAAAAAAAAAAMAbXAzKDNCMzFFN0QyNDc4
MDg3OEU2NTJEREMyNzVCOUY0QjM5MjdBOTI1METXWFAAAAAAAARjMjAyKAA3RDY2MG9rMkFNUEVl
bkRaaFNvUFhGX3ptUGNBT01mUHFlOVJNN2NHJRNBVw==
'/*!*/;
### DELETE FROM `test`.`demo`
### WHERE
###   @1=5 /* LONGINT meta=0 nullable=0 is_null=0 */
###   @2='mp3' /* VARSTRING(512) meta=512 nullable=0 is_null=0 */
###   @3='3B31E7D24780878E652DDC275B9F4B3927A9250D' /* STRING(160) meta=65184 nullable=0 is_null=0 */
###   @4=5265623 /* LONGINT meta=0 nullable=0 is_null=0 */
###   @5='c202' /* VARSTRING(80) meta=80 nullable=0 is_null=0 */
###   @6='7D660ok2AMPEenDZhSoPXF_zmPcAOMfPqe9RM7cG' /* VARSTRING(1020) meta=1020 nullable=0 is_null=0 */
# at 869
#181128 10:37:35 server id 1  end_log_pos 900 CRC32 0xebd8c4ad  Xid = 35
COMMIT/*!*/;
```

- [The Binary Log](https://dev.mysql.com/doc/refman/5.7/en/binary-log.html)

## 参考文献

- [MySQL Server Logs](https://dev.mysql.com/doc/refman/5.7/en/server-logs.html)
