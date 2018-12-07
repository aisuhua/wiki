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

备份完成后，需要对表解除锁定。

```
UNLOCK TABLE;
```
