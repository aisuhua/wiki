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
