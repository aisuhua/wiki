## 恢复表

备份表

```sh
mysqldump tutorial > tutorial.sql
```

手工创建一个用于恢复的数据库

```sql
create database tutorial
```

将表恢复到该数据库

```sh
mysql -h localhost -u root -p tutorial < tutorial.sql
```

### 其他

如存在 utf8mb4 编码的数据，备份时需指定编码，恢复步骤不变。

```sh
mysqldump --default-character-set=utf8mb4 tutorial > tutorial.sql
```

## 恢复库

备份库

```sh
mysqldump --databases tutorial > tutorial.sql
```

恢复库

```sh
mysql -h localhost -u root -p < tutorial.sql
```

恢复多个库的步骤是一样的。

## 故障恢复

9 点做了全量备份，14 点数据库出现故障，恢复步骤如下：

1. 先使用 9 点钟的备份文件进行恢复；
2. 然后使用 9 点 ~ 14 点的 binlog 进行重做，最终实现完全恢复。
