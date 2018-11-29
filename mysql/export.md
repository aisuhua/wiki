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

以 CSV 格式导出数据

```sql
select * from demo into outfile '/var/lib/mysql-files/outfile.csv' 
fields terminated by ',' optionally enclosed by '"' 
lines terminated by '\n';
```

- [SELECT ... INTO Syntax](https://dev.mysql.com/doc/refman/5.7/en/select-into.html)
