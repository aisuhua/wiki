# SQL 模式

The MySQL server can operate in different SQL modes, and can apply these modes differently for different clients, 
depending on the value of the sql_mode system variable. 
DBAs can set the global SQL mode to match site server operating requirements, 
and each application can set its session SQL mode to its own requirements.

Modes affect the SQL syntax MySQL supports and the data validation checks it performs. 
This makes it easier to use MySQL in different environments and to use MySQL together with other database servers.

常用的 SQL 模式：

- ANSI
- STRICT_TRANS_TABLES
- TRADITIONAL（默认）

除了 ANSI 是非严格模式，另外两种都是严格模式。

## 基本使用

查看当前的 SQL 模式

```sql
select @@sql_mode;
```

修改为非严格模式。

```sql
set sql_model = 'ANSI';
```

插入数据时出现字符串过长而报错与 `STRICT_TRANS_TABLES` 模式有关。

```
ERROR 1406 (22001): Data too long for column 'name' at row 1 
```

可以去掉该模式限制，例如：

```sql
set sql_model = 'ONLY_FULL_GROUP_BY,NO_ZERO_IN_DATE';
```

- [Server SQL Modes](https://dev.mysql.com/doc/refman/5.7/en/sql-mode.html#sql-mode-strict)
