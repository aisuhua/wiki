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
