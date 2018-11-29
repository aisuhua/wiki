## LOAD DATA INFILE

导出表数据

```sql
select * from demo into outfile '/var/lib/mysql-files/demo.txt'
```

先手工创建用于导入数据的表

```sql
create table demo (id int, name varchar(50))
```

将数据导入该表

```sql
load data infile '/var/lib/mysql-files/demo.txt' into table demo
```

### 其他

导出时自定义分隔符。

```sql
select * from demo into outfile '/var/lib/mysql-files/demo.txt' 
fields terminated by ',' enclosed by '"';
```

那么导入时也需要指定一样的分隔符。

```sql
load data infile '/var/lib/mysql-files/demo.txt' into table demo 
fields terminated by ',' enclosed by '"';
```
