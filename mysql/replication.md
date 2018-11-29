# 复制

主从服务器规划

| 服务器名称   | IP             | 用途          |
| ------------ | -------------- | ------------- |
| DB Master | 192.168.1.40 | 主数据库  |
| DB Slave  | 192.168.1.41 | 从数据库 |

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
cd /var/lib
tar cf /tmp/mysql.tar ./mysql
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

删除 auto.cnf 文件，防止与主库的 server UUIDs 冲突，MySQL 启动时会自动生成新的 auto.conf。

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

初始化从库配置，包括指定复制使用的用户、主库服务器 IP、端口以及开始执行复制的日志文件和位置等。

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
show processlist;
```

查看复制的状态

```sql
show slave status\G
```

## 参考文献

- [Configuring Replication](https://dev.mysql.com/doc/refman/5.7/en/replication-configuration.html)
- [故障案例：主从同步报错FATAL ERROR: THE SLAVE I/O ...](http://zhangbin.junxilinux.com/?p=793)
