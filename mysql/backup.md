## mysqldump

### 导出表

导出所有表

```sh
mysqldump tutorial > tutorial.sql
```

上面语句的完整版本

```sh
mysqldump -h localhost -u root -p tutorial > tutorial.sql
```

导出多张表

```sh
 mysqldump tutorial table1 table2 > tutorial.sql
```

### 导出库

导出单个库

```sh
mysqldump --databases tutorial > tutorial.sql
```

导出多个库

```sh
mysqldump --databases db1 --databases db2 > backup.sql
```

只导出指定的表

```sh
mysqldump --databases tutorial --tables table1 --tables table2 > tutorial.sql
```

### 其他

若存在 utf8mb4 编码的数据，导出时需要指定编码。

```sh
mysqldump --default-character-set=utf8mb4 --databases tutorial > backup.sql
```

只导出 DDL 语句，不含数据

```sh
mysqldump --no-data --databases tutorial > tutorial.sql
```

不导出数据库创建语句

```sh
mysqldump --no-create-db --databases tutorial > tutorial.sql
```

不导出数据表创建语句

```sh
mysqldump --no-create-info --databases tutorial > tutorial.sql
```

