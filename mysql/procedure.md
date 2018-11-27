# 存储过程和函数

两者都能实现将完成特定功能的多条 SQL 语句进行封装，以简单的方式供程序进行调用。

两者区别：

1. Function 总是返回一个值，而 Procedure 可以返回多个值或没有返回值，但不能使用 `return` 关键字返回；
2. Function 可以跟普通表达式或函数一样调用，而 Procedure 使用 `call` 关键字调用；
3. Procedure 可以使用 `INT`, `OUT`, `INOUT` 参数类型，而 Function 只有 `INT` 参数。

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

## 分布式 ID 的实现

### 创建 ID 递增计数表

```sql
create table sequence (
  `name` tinyint(3) unsigned not null,
  `val` bigint(20) unsigned not null,
  primary key (`name`)
) engine = innodb;
```

`name` 用于标识业务，`val` 为递增 ID。

### 初始化计数表

```sql
insert into sequence (name, val) values (1, 0);
```

由于是通过不停 `update` 同一个 `name` 的 `val` 实现值的递增，因此一开始必须存在该记录。

### 添加获取分布式 ID 的函数

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

### 获取分布式 ID

```sql
select sequence(1);
```

每次调用 `sequence(1)` 函数都可以获取一个全局唯一的 ID。

## 批量获取分布式 ID

该例子是上面例子的衍生。

### 添加批量获取 ID 的存储过程

```sql
create procedure `sequence_batch`(in name tinyint, in num int)
begin
    declare s int;
    set session sql_log_bin = off;
    set s = 0;
    create temporary table if not exists tb (id bigint) engine = myisam;
    start transaction;
    while s < num do
        insert into tb select sequence(name);
        set s = s +1;
    end while;
    commit;
    select * from tb;
    drop table tb;
    set session sql_log_bin = on;
end
```

根据所需的 ID 数量，将逐个生成的 ID 先存放在一个临时表，最后将它们一次性全部读取出来作为结果返回。

### 获取多个分布式 ID

```sql
call sequence_batch(1, 1000);
```

单次获取的 ID 数量越多，该方法所需的执行时间就越长。

## 游标

- 在存储过程和函数中，可以使用游标对结果集进行遍历处理，以便对每行数据进行操作。
- 有时，需要在检索出来的行中前进或后退一行或多行，这就是使用游标的原因。
- 游标主要用于交互式的应用，其中用户滚动屏幕上的数据，并对数据进行浏览或作出变更。

## 触发器

在某个表发生更改时自动触发执行某些语句，添加、删除、修改 3 个操作都支持使用触发器。

### 插入后触发

```sql
create trigger neworder after insert on orders 
for each row select NEW.order_num into @order_num;
```

`NEW` 新插入的记录。

### 更新后触发

```sql
create trigger updateorder before update on orders 
for each row set NEW.order_state = upper(NEW.order_state);
```

`OLD` 更新前的记录，`NEW` 更新后的记录。

### 删除后触发

```sql
create trigger deleteorder before delete on orders 
for each row
begin
    insert into archive_orders(order_num, order_date, cust_id)
    values (OLD.order_num, OLD.order_date, OLD.cust_id);
end;
```

`OLD` 被删除的记录。
