# 半同步复制

## Master

安装 semisync_master 插件

```sql
INSTALL PLUGIN rpl_semi_sync_master SONAME 'semisync_master.so';
```

查看已安装的插件

```sql
SHOW PLUGINS
```

启用半同步复制功能

```sql
SET GLOBAL rpl_semi_sync_master_enabled = 1
```

或修改配置文件

```cnf
[mysqld]
rpl_semi_sync_master_enabled = 1
```

查看系统变量

```sql
mysql> show variables like "rpl%";
```

查看运行状态

```sql
show status like "rpl%";
```

## Slave

安装 semisync_slave 插件

```sql
INSTALL PLUGIN rpl_semi_sync_slave SONAME 'semisync_slave.so';
```

查看已安装的插件

```sql
SHOW PLUGINS
```

启用半同步复制功能

```sql
SET GLOBAL rpl_semi_sync_slave_enabled = 1
```

重启 I/O 线程，否则不会切换到半同步复制。

```sql
STOP SLAVE IO_THREAD;
START SLAVE IO_THREAD;
```

或修改配置文件

```cnf
[mysqld]
rpl_semi_sync_slave_enabled=1
```

查看系统变量

```sql
show variables like "rpl%";
```

查看运行状态

```sql
show status like "rpl%";
```

- [Semisynchronous Replication](https://dev.mysql.com/doc/refman/5.7/en/replication-semisync.html)
