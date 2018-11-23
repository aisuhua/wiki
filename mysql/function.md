获取最新的自增 ID

```sql
select last_insert_id();
```

将时间转换成时间戳

```sql
select unique_timestamp('2018-10-20 17:36:20');
```

将时间戳转换成时间

```sql
select from_unixtime(1540028180);
```

- [12.1 Function and Operator Reference](https://dev.mysql.com/doc/refman/5.7/en/func-op-summary-ref.html)
