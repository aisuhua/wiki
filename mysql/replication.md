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






