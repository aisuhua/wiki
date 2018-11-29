## mysqldump

mysqldump 工具用于备份数据库以及在不同的数据库之间进行数据迁移。

### 备份表

备份所有表

```sh
mysqldump tutorial > tutorial.sql
```

上面语句的完整版本

```sh
mysqldump -h localhost -u root -p tutorial > tutorial.sql
```

备份部分表

```sh
 mysqldump tutorial table1 table2 > tutorial.sql
```

### 其他

将数据和建表语句备份为不同文件。

```sh
mysqldump tutorial -T /var/lib/mysql-files
```

demo.sql 为建表语句，demo.txt 只包含数据。

```sh
shell> ls /var/lib/mysql-files
demo.sql  demo.txt  test.sql  test.txt
```

### 备份库

备份单个库

```sh
mysqldump --databases tutorial > tutorial.sql
```

备份多个库

```sh
mysqldump --databases db1 --databases db2 > backup.sql
```

### 其他

备份部分表

```sh
mysqldump --databases tutorial --tables table1 --tables table2 > tutorial.sql
```

若存在 utf8mb4 编码的数据，备份时需要指定编码。

```sh
mysqldump --default-character-set=utf8mb4 --databases tutorial > tutorial.sql
```

只备份 DDL 语句，不含数据

```sh
mysqldump --no-data --databases tutorial > tutorial.sql
```

不备份数据库创建语句

```sh
mysqldump --no-create-db --databases tutorial > tutorial.sql
```

不备份数据表创建语句

```sh
mysqldump --no-create-info --databases tutorial > tutorial.sql
```

备份时不包含注释信息

```sh
mysqldump --databases tutorial --compact > tutorial.sql
```

### 备份所有库

备份所有数据库

```sql
mysqldump --all-databases > all.sql
```

- [mysqldump — A Database Backup Program](https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html)

