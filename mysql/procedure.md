# 存储过程和函数

两者都能实现将完成特定功能的多条 SQL 语句进行封装，以简单的方式供程序进行调用。

## 函数

简单示例

```sql
create function hello (s char(20)) returns char(50) 
return concat('hello, ', s, '!');
```

删除函数

```sql
drop function hello;
```

### 分布式 ID 的实现

创建 ID 递增计数表

```sql
create table sequence (
  `name` tinyint(3) unsigned not null,
  `val` bigint(20) unsigned not null,
  primary key (`name`)
) engine = innodb;
```

初始化计数表

```sql
insert into sequence (name, val) values (1, 1);
```

添加获取分布式 ID 的函数

```sql
create function sequence (n int) returns bigint
begin
    declare result  bigint unsigned;
    update sequence set val = last_insert_id(val+1) where name = n;
    set result = last_insert_id();
    return result;
end
```

获取分布式 ID

```sql
select sequence(1);
```

