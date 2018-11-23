## 索引管理

添加索引

```sql
ALTER TABLE student ADD INDEX name (`name`);
ALTER TABLE student UNIQUE INDEX uid (`uid`);
ALTER TABLE student add UNIQUE INDEX uid (`uid`), ADD INDEX name (`name`),ADD INDEX age (`age`);
```

删除索引

```sql
alter table student drop index name
```
