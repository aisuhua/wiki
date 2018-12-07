# 备份与恢复

备份的分类

- 逻辑备份和物理备份；
  - 物理备份是直接 copy 数据文件，需停服务或锁表后才能进行，其中 InnoDB 类型的表只能停服务后才能完整备份；
  - 逻辑备份主要使用 mysqldump 工具进行；
- 在线备份和离线备份；
  - 又名热备份和冷备份，还有温备份即表示服务还运行着，但是不能做任何修改数据的操作；
- 完整备份和增量备份；
  - 增量备份即基于时间点的备份，也就是基于 binlog 的增量备份；
- 快照备份；
  - MySQL 本身并不支持，需借助如 LVM, or ZFS 等外部工具才能实现；

官方文档对此有详细的介绍：[Database Backup Methods](https://dev.mysql.com/doc/refman/5.7/en/backup-methods.html)

## 物理备份

对于已经停止服务的数据库，无论对于 MyISAM 还是 InnoDB 类型的表都可以进行物理备份，但它的局限性在于需要停止 MySQL 服务，这样会造成业务中断。以下是物理备份的操作过程，假设 MySQL 的数据目录在 `/var/lib/mysql`。

```
mysqladmin shutdown
cd /var/lib
tar cf /tmp/mysql.tar ./mysql
scp /tmp/mysql.tar ...
```

另外，MyISAM 类型的表时也可以在锁表后进行物理备份而无需停止服务，备份前需要先锁表，因此备份期间将无法对表进行更新操作。

```
FLUSH TABLES tbl_list WITH READ LOCK;
```

然后对数据文件进行备份。

```
cd /var/lib
tar cf /tmp/mysql.tar ./mysql
scp /tmp/mysql.tar ...
```

备份完成后，需要对表解除锁定，让数据库恢复正常服务。

```
UNLOCK TABLE;
```

## 在线备份

对于 InnoDB 类型的表来说，可以使用 `mysqldump` 实现在线备份而不用锁表，从而不影响线上业务。该命令也可以用来备份 MyISAM 表，但是备份期间这些不支持事务的表不能发生数据修改，否则将无法保证数据的一致性。下面是使用 `mysqldump` 在线备份 InnoDB 表的示例：

```
shell> mysqldump --single-transaction --flush-logs --master-data=2 \
  --all-databases > backup_sunday_1_PM.sql
```

- `--single-transaction` 该选项用于创建一个一致性的快照，保证备份数据的一致性。
- `--flush-logs` 在备份完成后刷新 binlog 日志，从而使 binlog 写入新的日志文件，方便日后进行增量备份和数据恢复。
- `--master-data=2` 将 binlog 日志的信息以注释形式写入到备份文件中。

使用了 `--master-data=2` 后，备份后的文件中将包含 binlog 日志文件名和位置，如下所示：

```
-- Position to start replication or point-in-time recovery from
-- CHANGE MASTER TO MASTER_LOG_FILE='gbichot2-bin.000007',MASTER_LOG_POS=4;
```

下次使用该备份文件进行数据恢复时，将从该文件开始重做备份后发生的修改，以达到最完整的数据恢复。其恢复过程如下：

```
mysql < backup_sunday_1_PM.sql
mysqlbinlog gbichot2-bin.000007 gbichot2-bin.000008 | mysql
```

- [Point-in-Time (Incremental) Recovery Using the Binary Log](https://dev.mysql.com/doc/refman/5.7/en/point-in-time-recovery.html)

为了保证数据的一致性，使用 `mysqldump` 对 MyISAM 表进行备份时应先锁表，跟它的物理备份前要锁表一样。

```
mysql> FLUSH TABLES tbl_list WITH READ LOCK;
```

然后备份 MyISAM 表，因为 `--single-transaction` 只对支持事务的表有效，所以这里不需要该选项。

```
mysqldump --flush-logs --master-data=2 --all-databases > backup_sunday_1_PM.sql
```

## 故障恢复

如果是由于 SQL 语句的误操作造成数据或表被删除的情况下，可以结合全量备份和 binlog 日志进行数据恢复。因为 binlog 日志的重要性，除了要进行全量的数据备份外，也必须定期进行 binlog 日志的备份（增量备份）。在存储上，MySQL 的数据和 binlog 最好存在不同的磁盘上，因为在数据盘损坏的情况下，还可以根据上一次的全量备份和服务器上的 binlog 日志进行数据恢复。

使用 binlog 进行数据恢复，有基于时间的恢复和基于位置的恢复。

基于时间的恢复：

```
mysqlbinlog --stop-datetime="2005-04-20 9:59:59" /var/log/mysql/bin.123456 | mysql -u root -p
mysqlbinlog --start-datetime="2005-04-20 10:01:00" /var/log/mysql/bin.123456 | mysql -u root -p
```

基于位置的恢复：

```
mysqlbinlog --stop-position=368312 /var/log/mysql/bin.123456 | mysql -u root -p
mysqlbinlog --start-position=368315 /var/log/mysql/bin.123456 | mysql -u root -p
```

- [Point-in-Time Recovery Using Event Times](https://dev.mysql.com/doc/refman/5.7/en/point-in-time-recovery-times.html)
- [Point-in-Time Recovery Using Event Positions](https://dev.mysql.com/doc/refman/5.7/en/point-in-time-recovery-positions.html)

## 参考文献

- [Backup and Recovery](https://dev.mysql.com/doc/refman/5.7/en/backup-and-recovery.html) 官方文档对备份和恢复做了详细的说明，应视作日后主要的参考来源。
