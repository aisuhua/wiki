# 存储过程和函数

两者都能实现将完成特定功能的多条 SQL 脚本进行封装，以简单的方式供程序进行调用。

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

## 

