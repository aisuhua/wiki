## 主从同步维护

![Alt text](img/master_slave_sync.jpg)

## 查看复制状态

从库落后于主库的时长

```sql
mysql> show slave status\G
Seconds_Behind_Master: 0
```

- [MySql Replication - slave lagging behind master](https://stackoverflow.com/questions/8547827/mysql-replication-slave-lagging-behind-master)
