# 存储过程和函数

两者都能实现将完成特定功能的多条 SQL 语句进行封装，以简单的方式供程序进行调用。

## 函数

添加函数

```sql
create function hello (s char(20)) returns char(50) 
return concat('hello, ', s, '!');
```

查看函数定义

```sql
show create function sequence\G
```

调用函数

```sql
select hello('World');
```

删除函数

```sql
drop function hello;
```

## 存储过程

添加存储过程

```sql
create procedure simpleproc (out param1 int)
begin
  select count(*) into param1 from t;
end
```

调用存储过程

```sql
CALL simpleproc(@a);
```

获取返回值

```sql
SELECT @a;
```

查看存储过程的定义

```sql
show create procedure simpleproc\G
```

删除存储过程

```sql
drop procedure simpleproc;
```

- [13.1.16 CREATE PROCEDURE and CREATE FUNCTION Syntax](https://dev.mysql.com/doc/refman/5.7/en/create-procedure.html)

## 示例

### 分布式 ID 的实现

- 创建 ID 递增计数表

```sql
create table sequence (
  `name` tinyint(3) unsigned not null,
  `val` bigint(20) unsigned not null,
  primary key (`name`)
) engine = innodb;
```

`name` 用于标识业务，`val` 为递增 ID。

- 初始化计数表

```sql
insert into sequence (name, val) values (1, 0);
```

由于是通过不停 `update` 同一个 `name` 的 `val` 实现值的递增，因此一开始必须存在该记录。

- 添加获取分布式 ID 的函数

```sql
create function sequence (n tinyint) returns bigint
begin
    declare result bigint unsigned;
    update sequence set val = last_insert_id(val+1) where name = n;
    set result = last_insert_id();
    return result;
end
```

`update` 语句是原子的，而 `last_insert_id()` 在不同连接之间相互不影响，因此可以通过该函数获取全局唯一的 ID。

- 获取分布式 ID

```sql
select sequence(1);
```

每次调用 `sequence(1)` 函数都可以获取一个全局唯一的 ID。

