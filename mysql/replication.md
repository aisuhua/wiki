## master

设置 server id 和 开启 binlog 日志

```cnf
[mysqld]
server-id = 1
log-bin = /var/log/mysql/mysql-bin.log
```

重启服务

```sh
service mysql restart
```

创建用于复制的帐号

```sql
CREATE USER 'repl'@'192.168.1.%' IDENTIFIED BY 'password';
GRANT REPLICATION SLAVE ON *.* TO 'repl'@'192.168.1.%';
```

设定为读锁定模式，此时主库不能进行修改操作。

```sql
FLUSH TABLES WITH READ LOCK
```

查看当前的 binlog 文件名和偏移量。从库启动后，将会从该位置开始进行数据恢复。

```sql
mysql> SHOW MASTER STATUS;
+------------------+----------+--------------+------------------+-------------------+
| File             | Position | Binlog_Do_DB | Binlog_Ignore_DB | Executed_Gtid_Set |
+------------------+----------+--------------+------------------+-------------------+
| mysql-bin.000002 |      154 |              |                  |                   |
+------------------+----------+--------------+------------------+-------------------+
```

停止服务

```sh
mysqladmin shutdown
```

复制数据文件，假设数据存放在 `/var/lib/mysql` 目录。

```sh
tar cf /tmp/mysql.tar /var/lib/mysql
```

启动服务

```sh
service mysql start
```

将数据文件复杂到 slave 服务器。

```sh
scp /tmp/mysql.tar root@192.168.1.41:/tmp
```

## slave

停止服务

```sql
mysqladmin shutdown
```

将数据文件解压到数据目录。

```sh
tar xf /tmp/mysql.tar -C /var/lib/
```

删除 auto.cnf 文件，防止 server UUIDs 冲突。

```sh
rm /var/lib/mysql/auto.cnf
```

启动服务

```sh
service mysql start
```

设置 server id 

```cnf
[mysqld]
server-id = 1
```

重启服务

```sh
service mysql restart
```

初始化主从配置，包括指定复杂使用的用户、主数据库服务器 IP、端口以及开始执行复制的日志文件和位置等。

```sql
CHANGE MASTER TO
  MASTER_HOST='192.168.1.40',
  MASTER_USER='repl',
  MASTER_PASSWORD='123456',
  MASTER_LOG_FILE='mysql-bin.000002',
  MASTER_LOG_POS=154;
```

启动复制服务

```sql
START SLAVE;
```

查看复制进程

```sql
mysql> show processlist;
+----+-------------+-----------+----------+---------+------+--------------------------------------------------------+------------------+
| Id | User        | Host      | db       | Command | Time | State                                                  | Info             |
+----+-------------+-----------+----------+---------+------+--------------------------------------------------------+------------------+
|  1 | system user |           | NULL     | Connect | 2509 | Slave has read all relay log; waiting for more updates | NULL             |
|  2 | system user |           | NULL     | Connect | 2687 | Waiting for master to send event                       | NULL             |
|  5 | root        | localhost | tutorial | Query   |    0 | starting                                               | show processlist |
+----+-------------+-----------+----------+---------+------+--------------------------------------------------------+------------------+
3 rows in set (0.00 sec)
```

查看复制的状态

```sql
mysql> show slave status\G
*************************** 1. row ***************************
               Slave_IO_State: Waiting for master to send event
                  Master_Host: 192.168.1.40
                  Master_User: repl
                  Master_Port: 3306
                Connect_Retry: 60
              Master_Log_File: mysql-bin.000002
          Read_Master_Log_Pos: 697
               Relay_Log_File: wp-db2-relay-bin.000004
                Relay_Log_Pos: 910
        Relay_Master_Log_File: mysql-bin.000002
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
          Exec_Master_Log_Pos: 697
              Relay_Log_Space: 1284
              Until_Condition: None
               Until_Log_File: 
                Until_Log_Pos: 0
           Master_SSL_Allowed: No
           Master_SSL_CA_File: 
           Master_SSL_CA_Path: 
              Master_SSL_Cert: 
            Master_SSL_Cipher: 
               Master_SSL_Key: 
        Seconds_Behind_Master: 0
Master_SSL_Verify_Server_Cert: No
                Last_IO_Errno: 0
                Last_IO_Error: 
               Last_SQL_Errno: 0
               Last_SQL_Error: 
  Replicate_Ignore_Server_Ids: 
             Master_Server_Id: 1
                  Master_UUID: 868b76f0-ec9a-11e8-950c-080027c8825e
             Master_Info_File: /var/lib/mysql/master.info
                    SQL_Delay: 0
          SQL_Remaining_Delay: NULL
      Slave_SQL_Running_State: Slave has read all relay log; waiting for more updates
           Master_Retry_Count: 86400
                  Master_Bind: 
      Last_IO_Error_Timestamp: 
     Last_SQL_Error_Timestamp: 
               Master_SSL_Crl: 
           Master_SSL_Crlpath: 
           Retrieved_Gtid_Set: 
            Executed_Gtid_Set: 
                Auto_Position: 0
         Replicate_Rewrite_DB: 
                 Channel_Name: 
           Master_TLS_Version: 
1 row in set (0.00 sec)
```
