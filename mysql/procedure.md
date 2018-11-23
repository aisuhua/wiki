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

#### 创建 ID 递增计数表

```sql
create table sequence (
  `name` tinyint(3) unsigned not null,
  `val` bigint(20) unsigned not null,
  primary key (`name`)
) engine = innodb;
```

`name` 用于标识业务，`val` 为递增 ID。

#### 初始化计数表

```sql
insert into sequence (name, val) values (1, 1);
```

由于是通过不停 `update` 同一个 `name` 的 `value` 实现值的递增，因此必须存在该记录。

#### 添加获取分布式 ID 的函数

```sql
create function sequence (n int) returns bigint
begin
    declare result bigint unsigned;
    update sequence set val = last_insert_id(val+1) where name = n;
    set result = last_insert_id();
    return result;
end
```

`update` 语句是原子的，而 `last_insert_id()` 在不同连接之间相互不影响，应此可以通过该函数获取全局唯一的 ID。

#### 获取分布式 ID

```sql
select sequence(1);
```

每次调用 `sequence(1)` 函数都可以获取一个全局唯一的 ID。

