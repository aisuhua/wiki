## 索引管理

添加索引

```sql
alter table student add index name (`name`);
alter table student add unique index uid (`uid`);
alter table student add unique index uid (`uid`), add index name (`name`),add index age (`age`);
```

删除索引

```sql
alter table student drop index uid;
alter table student drop index name, drop index age;
```

查看索引

```sql
show create table student\G
```

给值有重复的列添加唯一索引会报错，可以使用以下方法找出值重复的行。

```sql
SELECT unique_id,COUNT(unique_id)
FROM yourtblname
GROUP BY unique_id
HAVING COUNT(unique_id) >1
```

- [#1062 - Duplicate entry '' for key 'unique_id' When Trying to add UNIQUE KEY (MySQL)](https://stackoverflow.com/questions/17823322/1062-duplicate-entry-for-key-unique-id-when-trying-to-add-unique-key-my)

使用 `IGNORE` 参数可以实现在添加唯一索引的同时将值重复的记录删除只保留一行。

```sql
ALTER IGNORE TABLE mytable ADD UNIQUE INDEX myindex (A, B, C, D);
```

但是，这种方式只在 MySQL 5.7.4 之前的版本有效。

- [Removing duplicates with unique index](https://dev.mysql.com/doc/refman/5.7/en/alter-table.html)
