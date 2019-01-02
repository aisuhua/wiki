## 查看帮助

查看 show 命令的用法

```sql
help show
```

查看 select 命令的用法

```sql
help select
```

## 查看服务器状态

查看运行状态

```sql
show status
```

查看所有活动进程

```sql
show processlist
```

查看错误信息

```sql
show errors
```

## 存储引擎

查看所有引擎

```sql
show engines
```

## 库和表的状态

查看表结构

```sql
show create table customers
```

查看表的统计信息：总行数、每行平均大小等

```sql
show table status like 'user' \G
```

## 字符集

查看所有字符集

```sql
show character set
```

查看所有校对集

```sql
show collation
```

查看当前字符集

```sql
show variables like "character%"
```

查看当前校对集

```sql
show variables like "collation%"
```
