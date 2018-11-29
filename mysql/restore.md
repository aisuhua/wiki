## 恢复表

备份表

```sh
mysqldump tutorial > tutorial.sql
```

先手工创建准备恢复的数据库

```sql
create database tutorial
```

恢复表及全部数据

```sh
mysql -h localhost -u root -p tutorial < tutorial.sql
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
