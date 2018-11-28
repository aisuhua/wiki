## mysqldump

mysqldump 工具用于备份数据库以及在不同的数据库之间进行数据迁移。

### 导出表

导出所有表

```sh
mysqldump tutorial > tutorial.sql
```

上面语句的完整版本

```sh
mysqldump -h localhost -u root -p tutorial > tutorial.sql
```

导出部分表

```sh
 mysqldump tutorial table1 table2 > tutorial.sql
```

### 其他

将数据和建表语句导出为不同文件

```sh
mysqldump tutorial -T /var/lib/mysql-files
```

demo.sql 只包含建表语句，demo.txt 只包含数据

```sh
shell> ls /var/lib/mysql-files
demo.sql  demo.txt  test.sql  test.txt
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

### 其他

导出部分表

```sh
mysqldump --databases tutorial --tables table1 --tables table2 > tutorial.sql
```

若存在 utf8mb4 编码的数据，导出时需要指定编码。

```sh
mysqldump --default-character-set=utf8mb4 --databases tutorial > tutorial.sql
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

导出时不包含注释信息

```sh
mysqldump --databases tutorial --compact > tutorial.sql
```

- [mysqldump — A Database Backup Program](https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html)

## SELECT ... INTO

将查询结果导出到文件

```sql
select * from demo into outfile '/var/lib/mysql-files/outfile.txt'
```

导出的内容格式

```sh
shell> cat outfile.txt 
1       google
2       facebook
3       suhua is a good boy.
```

默认的分隔符

```sql
FIELDS TERMINATED BY '\t' ENCLOSED BY '' ESCAPED BY '\\'
LINES TERMINATED BY '\n' STARTING BY ''
```

导出时，MySQL 会自动对数据中与分隔符一样的字符进行转义，所以无需担心分隔符会与数据冲突。

```text
a\tb\nc\d -> a\\tb\\nc\\d
```

### 其他

自定义分隔符

```sql
select * from demo into outfile '/var/lib/mysql-files/outfile.txt' 
fields terminated by ',' enclosed by '"';
```

以 CSV 格式导出

```sql
select * from demo into outfile '/var/lib/mysql-files/outfile.csv' 
fields terminated by ',' optionally enclosed by '"' 
lines terminated by '\n';
```

- [SELECT ... INTO Syntax](https://dev.mysql.com/doc/refman/5.7/en/select-into.html)
