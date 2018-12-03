## 主从同步维护

![Alt text](img/master_slave_sync.jpg)

## 查看复制状态

从库落后主库的时长

```sql
mysql> show slave status\G
Seconds_Behind_Master: 0
```

- [MySql Replication - slave lagging behind master](https://stackoverflow.com/questions/8547827/mysql-replication-slave-lagging-behind-master)

## 如何提供复制速度

多线程复制：`DATABASE`, `LOGICAL_CLOCK`。

```
slave_parallel_type
slave_parallel_workers
```

- [sysvar_slave_parallel_type](https://dev.mysql.com/doc/refman/5.7/en/replication-options-slave.html#sysvar_slave_parallel_type)
- [Mysql 5.7 主从复制的多线程复制配置方式](https://www.jianshu.com/p/a1ff89122266)
