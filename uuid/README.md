# Sharding ids in MySQL

参考 [Instagram](https://instagram-engineering.com/sharding-ids-at-instagram-1cf5a71e5a5c)，使用 MySQL 来生成分布式的 ID。

Instagram unique ID 的组成:

- 41 bits 表示 Timestamp (毫秒), 能自定义起始时间 epoch
- 13 bits 表示 每个 logic Shard 的代号 (最大支持 8 x 1024 个 logic Shards)
- 10 bits 表示 sequence number; 每个 Shard 每毫秒最多可以生成 1024 个 ID

## 实现过程

### sequence

记录了最新的 ID，用于计算分布式 ID 的最后 10 位序列号。

```sql
create table sequence (
  `name` varchar(64) not null,
  `value` bigint unsigned not null,
  primary key (`name`)
) engine=innodb;
```

### nextval

更新 `sequence` 表并返回最新的值。

```sql
create function nextval (sequence_name varchar(64)) returns bigint
begin
    declare result bigint unsigned;
    update sequence set value = last_insert_id(value + 1) where name = sequence_name;
    set result = last_insert_id();
    return result;
end
```

### next_id

生成分布式 ID

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

- shard_id 在分库分表的情况下，该值至少在每个库都不一样，也可以每个分表都不一样，该值还可以从外部传入。
- seq_id 为递增序列号，ID 的生成速度主要取决于这里，也可以用其他更高效的方式生成，然后从外部传入该值。
- 在 shard_id 相同的情况下，每毫秒最多可生成 1024 个 unique ID（每秒约100万）。
- 64 位的分布式 ID 还有更多的组合方式，可以参考文献「分布式ID生成器」。
- 41 位用于记录毫秒数，能够使用大概 69 年。
- our_epoch 起始时间可以自定义，一般为系统上线的时间。

### next_ids

批量生成分布式 ID

```sql
create procedure `next_ids`(in seq_name varchar(64), in num int)
begin
    declare counter int default 0;
    create temporary table if not exists tb (id bigint unsigned) engine = myisam;

    start transaction;
    while counter < num do
        insert into tb select next_id(seq_name);
        set counter = counter + 1;
    end while;
    commit;

    select * from tb;
    drop table tb;
end
```

- 为了加快速度，使用了事务，但是可能会堵塞住其他的进程，是否应该采用事务应视具体业务而定。

## 初始化

新增 default 序列(sequence)。

```sql
insert into sequence values ('default', 0);
```

## 使用

获取一个分布式 ID。

```sql
mysql> select next_id('default');
+---------------------+
| next_id('default')  |
+---------------------+
|     535127205610497 |
+---------------------+
1 row in set (0.00 sec)
```

获取多个分布式 ID。

```sql
mysql> call next_ids('default', 3);
+-----------------+
| id              |
+-----------------+
| 535675904459778 |
| 535675904459779 |
| 535675912848388 |
+-----------------+
3 rows in set (0.01 sec)
```

## 其他

对分布式的 ID 进行拆分和分析。

```php
<?php

date_default_timezone_set("PRC");

$id = '535127205610497';
$str = base_convert($id, 10, 2);
echo $str, '(', strlen($str), ')', PHP_EOL;

$cur_millis = substr($str, 0, -23);
$shard_id = substr($str, -23, -10);
$seq_id = substr($str, -10);

echo $cur_millis, '(', strlen($cur_millis), ')', PHP_EOL;
echo $shard_id, '(', strlen($shard_id), ')', PHP_EOL;
echo $seq_id, '(', strlen($seq_id), ')', PHP_EOL;

$cur_millis = base_convert($cur_millis, 2, 10);
$shard_id = base_convert($shard_id, 2, 10);
$seq_id = base_convert($seq_id, 2, 10);

echo $cur_millis, PHP_EOL;
echo $shard_id, PHP_EOL;
echo $seq_id, PHP_EOL;

echo date('Y-m-d H:i:s', substr($cur_millis + 1543939200000, 0, -3)), PHP_EOL;
```

上面输出结果如下：

```
1111001101011001000000011000000000000010000000001(49)
11110011010110010000000110(26)
0000000000001(13)
0000000001(10)
63792134
1
1
2018-12-05 17:43:12
```

## 参考文献

- [Sharding & IDs at Instagram](https://instagram-engineering.com/sharding-ids-at-instagram-1cf5a71e5a5c)
- [A BETTER ID GENERATOR FOR POSTGRESQL](https://rob.conery.io/2014/05/28/a-better-id-generator-for-postgresql/)
- [Ticket Servers: Distributed Unique Primary Keys on the Cheap](http://code.flickr.net/2010/02/08/ticket-servers-distributed-unique-primary-keys-on-the-cheap/)
- [PostgreSQL - 序列（Sequence）](https://n3xtchen.github.io/n3xtchen/postgresql/2015/04/10/postgresql-sequence)
- [分布式ID生成器](https://mp.weixin.qq.com/s?__biz=MjM5ODYxMDA5OQ==&mid=2651960245&idx=1&sn=5cef3d8ca6a3e6e94f61e0edaf985d11&chksm=bd2d06698a5a8f7fc89056af619b9b7e79b158bceb91bdeb776475bc686721e36fb925904a67&scene=21#wechat_redirect)
- [分布式系统中唯一 ID 的生成方法 ](http://einverne.github.io/post/2017/11/distributed-system-generate-unique-id.html)
- [How many years of millisecond timestamps can be represented by 41 bits?](https://stackoverflow.com/questions/29109807/how-many-years-of-millisecond-timestamps-can-be-represented-by-41-bits)
- [Leaf——美团点评分布式ID生成系统](https://tech.meituan.com/MT_Leaf.html)
- [分布式架构系统生成全局唯一序列号的一个思路](https://mp.weixin.qq.com/s/F7WTNeC3OUr76sZARtqRjw)
