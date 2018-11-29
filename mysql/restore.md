## 恢复表

备份表

```sh
mysqldump tutorial > tutorial.sql
```

创建数据库

```sql
create database tutorial
```

恢复表

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

备份和恢复多个库的步骤是一样的。
