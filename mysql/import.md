## LOAD DATA INFILE

### 导出表数据

```sql
select * from demo into outfile '/var/lib/mysql-files/outfile.txt'
```

### 导入表数据

先手工创建用于导入数据的表

```sql
create table demo (id int, name varchar(50))
```

将数据导入该表

```sql
load data infile '/var/lib/mysql-files/outfile.txt' into table demo
```


