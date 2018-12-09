# 使用从库进行备份

```
# 加全局锁
FLUSH TABLES WITH READ LOCK;

# 此时，IO 线程能正常接收主库的 binlog 日志，
# 但是 SQL 线程无法再应用 relad log 的内容
mysql> show processlist;
+----+-------------+-----------+----------+---------+------+----------------------------------+------------------+
| Id | User        | Host      | db       | Command | Time | State                            | Info             |
+----+-------------+-----------+----------+---------+------+----------------------------------+------------------+
|  5 | root        | localhost | tutorial | Query   |    0 | starting                         | show processlist |
| 15 | system user |           | NULL     | Connect | 4348 | Waiting for master to send event | NULL             |
| 32 | system user |           | NULL     | Connect |   28 | Waiting for global read lock     | NULL             |
+----+-------------+-----------+----------+---------+------+----------------------------------+------------------+
3 rows in set (0.00 sec)

# 复制状态显示  Master_Log_File 和 Read_Master_Log_Pos 都与主库保持一致，因为 IO 线程能正常工作
# 而 Exec_Master_Log_Pos 说明有一部分 relay log 还没有执行。
mysql> show slave status\G
*************************** 1. row ***************************
               Slave_IO_State: Waiting for master to send event
                  Master_Host: 192.168.31.40
                  Master_User: repl
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000001
          Read_Master_Log_Pos: 3911
               Relay_Log_File: wp-db2-relay-bin.000002
                Relay_Log_Pos: 2284
        Relay_Master_Log_File: mysql-bin.000001
             Slave_IO_Running: Yes
            Slave_SQL_Running: Yes
              Replicate_Do_DB: 
          Replicate_Ignore_DB: 
           Replicate_Do_Table: 
       Replicate_Ignore_Table: 
      Replicate_Wild_Do_Table: 
  Replicate_Wild_Ignore_Table: 
                   Last_Errno: 0
                   Last_Error: 
                 Skip_Counter: 0
          Exec_Master_Log_Pos: 3640
              Relay_Log_Space: 2763
              Until_Condition: None
              

```

- [Using Replication for Backups](https://dev.mysql.com/doc/mysql-backup-excerpt/5.7/en/replication-solutions-backups.html)
