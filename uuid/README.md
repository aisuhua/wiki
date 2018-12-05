使用 MySQL 来生成分布式的 ID。

## 实现过程

sequence 记录了最新的递增 ID，该 ID 用于计算分布式 ID 的最后 10 位序列号。

```sql
create table sequence (
  `name` varchar(64) not null,
  `value` bigint unsigned not null,
  primary key (`name`)
) engine=innodb;
```

`nextval` 更新 `sequence` 表并返回最新递增的 ID。

```sql
create function nextval (sequence_name varchar(64)) returns bigint
begin
    declare result bigint unsigned;
    update sequence set value = last_insert_id(value + 1) where name = sequence_name;
    set result = last_insert_id();
    return result;
end
```

`next_id` 函数用于生成分布式 ID。

```sql
create function `next_id`(seq_name varchar(64)) returns bigint
begin
    declare result bigint unsigned;
    declare our_epoch bigint default 1543939200000;
    declare shard_id int default 1;
    declare seq_id bigint unsigned;
    declare cur_time char(12);
    declare cur_millis char(19);

    select nextval(seq_name) into seq_id;

    set cur_time = curtime(3);
    set cur_millis = concat(floor(unix_timestamp(concat(curdate(), ' ', left(cur_time, 8)))), right(cur_time, 3));

    set result = (cur_millis - our_epoch) << 23;
    set result = result | (shard_id % 8192) << 10;
    set result = result | (seq_id % 1024);

    return result;
end
```

`next_ids` 存储过程用于批量生成分布式 ID。

```sql
create procedure `next_ids`(in seq_name varchar(64), in num int)
begin
    declare counter int default 0;
    create temporary table if not exists tb (id bigint unsigned) engine = myisam;

    start transaction;
    while counter < num do
        insert into tb select next_id(seq_name);
        set counter = counter +1;
    end while;
    commit;

    select * from tb;
    drop table tb;
end
```
