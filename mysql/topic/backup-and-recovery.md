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

另外，MyISAM 类型的表时也可以在锁表后进行物理备份而无需停止服务，但是备份期间将无法对表进行更新操作。备份前先执行锁表并刷新缓存：

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
- `--flush-logs` 在备份完成后刷新 binlog 日志并生成新的日志文件，方便日后进行增量备份和数据恢复。
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

